-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW support_tickets_perspective AS
SELECT t.id,
    t.uuid,
    t.title,
    t.description,
    t.iam_account_id,
    t.iam_user_id,
    t.tags,
    t.is_closed,
    t.is_public,
    t.level,
    t.priority,
    t.status,
    t.common_category_id,
    t.response_time,
    t.first_response_at,
    t.resolved_at,
    t.sla_resolution_due_at,
    t.sla_response_breached,
    t.sla_resolution_breached,
    t.reopened_count,
    t.is_first_contact_resolution,
    t.object_id,
    t.object_type,
    t.responsible_user_id,
    t.time_spent,
    t.watcher_user_ids,
    t.watcher_account_ids,
    t.support_seeker_account_id,
    u.fullname,
    u.email,
    u.phone_number,
    u.pronoun,
    a.name,
    a.iam_account_type_id,
    sa.name AS support_seeker_name,
    ru.fullname AS responsible_name,
    cc.name AS category_name,
    cs.score AS csat_score,
    t.created_at,
    t.updated_at,
    t.deleted_at
   FROM support_tickets t
     LEFT JOIN iam_users u ON u.id = t.iam_user_id
     LEFT JOIN iam_accounts a ON a.id = t.iam_account_id
     LEFT JOIN iam_accounts sa ON sa.id = t.support_seeker_account_id
     LEFT JOIN iam_users ru ON ru.id = t.responsible_user_id
     LEFT JOIN common_categories cc ON cc.id = t.common_category_id
     LEFT JOIN LATERAL ( SELECT c.score
           FROM support_csats c
          WHERE c.support_ticket_id = t.id AND c.deleted_at IS NULL
          ORDER BY c.created_at DESC
         LIMIT 1) cs ON true
  WHERE t.deleted_at IS NULL;
