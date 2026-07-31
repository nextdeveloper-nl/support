-- PostgreSQL

CREATE TABLE support_kb_articles (
    id                  bigint NOT NULL DEFAULT nextval('support_kb_articles_id_seq'::regclass),
    uuid                uuid DEFAULT gen_random_uuid(),
    common_category_id  bigint,
    title               text NOT NULL,
    slug                text NOT NULL,
    body                text NOT NULL,
    excerpt             text,
    is_published        boolean NOT NULL DEFAULT false,
    view_count          integer NOT NULL DEFAULT 0,
    helpful_count       integer NOT NULL DEFAULT 0,
    not_helpful_count   integer NOT NULL DEFAULT 0,
    iam_account_id      bigint,
    iam_user_id         bigint,
    created_at          timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at          timestamp with time zone,
    CONSTRAINT support_kb_articles_common_category_id_fkey FOREIGN KEY (common_category_id) REFERENCES common_categories(id),
    CONSTRAINT support_kb_articles_pkey PRIMARY KEY (id)
);

CREATE INDEX support_kb_articles_category_idx ON public.support_kb_articles USING btree (common_category_id);
CREATE INDEX support_kb_articles_fts_idx ON public.support_kb_articles USING gin (to_tsvector('simple'::regconfig, ((COALESCE(title, ''::text) || ' '::text) || COALESCE(body, ''::text))));
CREATE INDEX support_kb_articles_slug_idx ON public.support_kb_articles USING btree (slug);
