# Support System — turn support into a competitive advantage

## The problem (why this exists)

Industry research across hosting and B2B SaaS shows customers don't churn because a
problem happened — they churn because they felt **abandoned, misunderstood, or
exploited** while fixing it. The recurring complaints:

- Slow first response (especially during downtime).
- Unskilled tier-1 agents → endless escalation, wrong troubleshooting.
- Generic, canned answers that ignore the customer's real configuration.
- Purely reactive support — nothing happens until something breaks.
- Support weaponized as an upsell ("upgrade for priority support").
- Weak self-serve / onboarding that floods support with basics.

Leaders win the opposite way: instant self-serve tier-0 with **honest human
escalation** (Klarna), in-product contextual help (Shopify), competing on **expertise +
first-contact resolution** (Kinsta), and turning good outcomes into **proof / CSAT**
(Zappos). The `NextDeveloper/support` module was a thin CRUD scaffold (only an
`is_closed` boolean, no workflow / SLA / CSAT / routing / KB / AI). This build turns it
into a real system, mapping each research pillar to a concrete feature.

| Research pillar | What we built |
|---|---|
| Slow response / feel abandoned | Real status workflow, threaded conversation, `first_response_at` clock, SLA timers + breach escalation |
| Unskilled tier-1 / escalation | Categories (reuse `common_categories`), agent expertise, auto-route to skilled agent, first-contact-resolution metric |
| Generic / over-automated answers | AI **agent-assist** (suggested reply) — human stays in the loop |
| Reactive instead of proactive | Hourly SLA-breach job escalates before the customer complains |
| Support as upsell | No upsell surfaces; success measured by CSAT + FCR |
| Turn outcomes into proof | Post-resolution CSAT (score + comment), reopen tracking, FCR flag |
| In-product, honest escalation | New KB + AI tier-0 deflection in the create flow that **always** offers "talk to a human" |

## Architecture

Schema is the source of truth: the `NextDeveloper\Generator` reads Postgres and renders
models/services/controllers/transformers/filters/requests/routes/perspectives. Schema
ships as raw SQL in `leo4/database/scripts/` (no Laravel migrations). Custom logic lives
**below** the `// EDIT AFTER HERE` marker; abstract services/transformers are always
regenerated.

### Schema (database/scripts/support_system_phase1.sql)

- **`support_tickets`** new columns: `status` (open→pending→waiting_on_customer→
  resolved→closed), `common_category_id` (FK → `common_categories`), `first_response_at`,
  `resolved_at`, `reopened_count`, `is_first_contact_resolution`, `sla_resolution_due_at`,
  `sla_response_breached`, `sla_resolution_breached`. Reuses `responsible_user_id`
  (assigned agent) and `response_time` (first-response SLA due). Partial index backs the
  breach-scan.
- **`common_categories`**: added `object_type`/`object_id` (lights up the existing
  `HasObject` trait) so the shared taxonomy is reused per module — ticket categories use
  `object_type = NextDeveloper\Support\Database\Models\Tickets`.
- **`support_ticket_comments`**: added `is_internal` (agent-only notes).
- New tables: **`support_kb_articles`** (+ tsvector FTS index), **`support_sla_policies`**
  (seeded 5 priority tiers), **`support_agent_expertise`**, **`support_csat`**.
- Views: `support_tickets_perspective` (denormalized: status, agent name, category name,
  SLA fields, latest csat_score) and `support_kb_articles_perspective`.

### Backend (`NextDeveloper/support`)

- **Actions** (`src/Actions/Tickets/`, extend `Commons\AbstractAction`, dispatched via
  `doAction`): `ChangeStatus` (transitions + resolved_at/reopened_count/FCR/is_closed
  sync + audit), `AssignTicket` / `UnassignTicket` (responsible_user_id), `AutoRouteTicket`
  (skill routing by category → least-loaded available agent), `EscalateOnSlaBreach`.
- **`TicketsService::create`** computes SLA due-dates from `support_sla_policies` by
  priority, normalizes a category uuid → id, and dispatches `AutoRouteTicket`.
- **`TicketCommentsService::create`** persists the comment (was a no-op) and stamps
  `first_response_at` on the first public agent reply.
- **`CheckTicketSlaBreachesJob`** (+ leo4 `leo:support-check-sla`, scheduled hourly):
  flags response/resolution breaches and escalates.
- **Roles**: `allowedOperations()` extended for the new tables; assignment/status ops added.
- Run `php artisan leo:register-actions` after adding/changing Actions.

### AI (reuses the existing bridge)

`leo4 App\Services\Support\TicketAiService` → `AIAssistanceService::request()` →
microservice `leo4.ai-assistance-v2` helpers (`App\AI\Support\Tickets\*`, registered with
`php artisan leo:register-ai-helpers`):

- `ReplySuggester` — drafts a reply for the agent to review (never auto-sent).
- `AutoCategorizer` — picks a category; the endpoint applies it server-side.
- `KbDeflector` — ranks KB articles and **always** returns `escalate_to_human: true`.

Synchronous leo4 endpoints (auth'd, under `/public/support`): `POST
tickets/{id}/suggest-reply`, `POST tickets/{id}/auto-categorize`, `POST kb/deflect`.

### Panel (`panel-v6.2`)

TS models + `api.ts` regenerated via `php generate-models.php`. Components under
`src/modules/support/`: `TicketDetail.vue` (conversation thread with public/internal
toggle, status control, assign-to-me/unassign, SLA timer + breach badges, CSAT widget,
AI suggest-reply + auto-categorize), `KbBrowser.vue` (search + helpful votes),
`KbDeflectionStep.vue` (in the create flow). `ListSupportTickets.vue` shows the real
status + SLA-breach badge and rows open the detail. Pages: `support/[id].vue`,
`support/kb/index.vue`. Nav: a Support module (Tickets · Knowledge Base) in `nav-items.ts`.

## Operating notes

- Status/assignment/routing/escalation are **queued Actions** — a queue worker must be
  running for them to take effect (the panel refetches shortly after, and NATS pushes
  model updates).
- Schedule `leo:support-check-sla --sync` is registered hourly in `app/Console/Kernel.php`.
- Seed ticket categories in `common_categories` with the platform domain and
  `object_type = NextDeveloper\Support\Database\Models\Tickets` (the SQL file has a
  commented seed). KB categories use `...\KbArticles`.
- End users reading the category list / KB need `common_categories` + `support_kb_articles`
  read access (the latter is in the support roles).

## Verification

- `psql \d support_tickets` — new columns + `uuid DEFAULT gen_random_uuid()`.
- tinker: create a ticket → `response_time`/`sla_resolution_due_at` set, `AutoRouteTicket`
  assigns an agent when a category + expertise exist; `ChangeStatus` to resolved →
  `resolved_at` + FCR; dispatch `CheckTicketSlaBreachesJob` on a past-due ticket → breach.
- `GET /api/support/tickets/actions` lists the new actions; AI: `POST
  /public/support/tickets/{id}/suggest-reply`.
- `route:list` is broken app-wide (unrelated missing controller) — verify routes via
  `app('router')->getRoutes()`.
- Panel: `npm run dev`; open a ticket, change status/assign/reply/rate; KB search.
