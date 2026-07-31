-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW support_kb_articles_perspective AS
SELECT k.id,
    k.uuid,
    k.common_category_id,
    k.title,
    k.slug,
    k.body,
    k.excerpt,
    k.is_published,
    k.view_count,
    k.helpful_count,
    k.not_helpful_count,
    k.iam_account_id,
    k.iam_user_id,
    cc.name AS category_name,
    u.fullname AS author_name,
    k.created_at,
    k.updated_at,
    k.deleted_at
   FROM support_kb_articles k
     LEFT JOIN common_categories cc ON cc.id = k.common_category_id
     LEFT JOIN iam_users u ON u.id = k.iam_user_id
  WHERE k.deleted_at IS NULL;
