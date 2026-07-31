-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW support_ticket_comments_perspective AS
SELECT stc.id,
    stc.uuid,
    stc.comment,
    stc.iam_account_id,
    stc.iam_user_id,
    iu.fullname,
    iu.email,
    iu.phone_number,
    iu.pronoun,
    ia.name,
    ia.iam_account_type_id,
    stc.support_ticket_id,
    stc.created_at,
    stc.updated_at,
    stc.deleted_at
   FROM support_ticket_comments stc
     JOIN iam_users iu ON stc.iam_user_id = iu.id
     JOIN iam_accounts ia ON ia.id = stc.iam_account_id;
