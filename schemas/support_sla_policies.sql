-- PostgreSQL

CREATE TABLE support_sla_policies (
    id                         bigint NOT NULL DEFAULT nextval('support_sla_policies_id_seq'::regclass),
    uuid                       uuid DEFAULT gen_random_uuid(),
    name                       text NOT NULL,
    priority                   smallint NOT NULL,
    response_target_minutes    integer NOT NULL,
    resolution_target_minutes  integer NOT NULL,
    is_active                  boolean NOT NULL DEFAULT true,
    iam_account_id             bigint,
    iam_user_id                bigint,
    created_at                 timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                 timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at                 timestamp with time zone,
    CONSTRAINT support_sla_policies_pkey PRIMARY KEY (id)
);

-- Added for applications that time their own ticket kinds (fixlean: alert cards). A policy with a
-- kind is chosen by that application from its conditions and never by applySlaDueDates, which keeps
-- timing ordinary support tickets (kind NULL) by priority alone.
ALTER TABLE public.support_sla_policies ADD COLUMN IF NOT EXISTS kind text; -- The ticket kind the policy times (fixlean: alert). NULL: ordinary support tickets.
ALTER TABLE public.support_sla_policies ADD COLUMN IF NOT EXISTS conditions jsonb NOT NULL DEFAULT '{}'::jsonb; -- The application's narrowing, e.g. fixlean {"categories": [uuid], "priorities": [int], "assets": [uuid]}; an empty list matches all.
ALTER TABLE public.support_sla_policies ADD COLUMN IF NOT EXISTS clock text NOT NULL DEFAULT '7x24'; -- 7x24, or workhours (work_days between work_start and work_end).
ALTER TABLE public.support_sla_policies ADD COLUMN IF NOT EXISTS work_days smallint[] NOT NULL DEFAULT '{1,2,3,4,5}'; -- ISO weekdays, 1 Monday .. 7 Sunday.
ALTER TABLE public.support_sla_policies ADD COLUMN IF NOT EXISTS work_start text; -- HH:MM
ALTER TABLE public.support_sla_policies ADD COLUMN IF NOT EXISTS work_end text; -- HH:MM
ALTER TABLE public.support_sla_policies ADD COLUMN IF NOT EXISTS due_soon_percent smallint NOT NULL DEFAULT 75; -- When "due soon" is announced, in percent of the target.
ALTER TABLE public.support_sla_policies ADD COLUMN IF NOT EXISTS escalation jsonb NOT NULL DEFAULT '[]'::jsonb; -- [{"after_minutes": int, "team_id": uuid|null, "user_id": uuid|null}]
DO $$ BEGIN
    ALTER TABLE public.support_sla_policies ADD CONSTRAINT support_sla_policies_clock_chk CHECK (clock IN ('7x24', 'workhours'));
EXCEPTION WHEN duplicate_object THEN NULL; END $$;
CREATE INDEX IF NOT EXISTS support_sla_policies_kind_idx ON public.support_sla_policies USING btree (iam_account_id, kind) WHERE ((deleted_at IS NULL) AND (kind IS NOT NULL));
