-- PostgreSQL

CREATE TABLE support_agent_expertises (
    id                  bigint NOT NULL DEFAULT nextval('support_agent_expertise_id_seq'::regclass),
    uuid                uuid DEFAULT gen_random_uuid(),
    iam_user_id         bigint NOT NULL,
    common_category_id  bigint NOT NULL,
    proficiency         smallint NOT NULL DEFAULT 1,
    is_available        boolean NOT NULL DEFAULT true,
    iam_account_id      bigint,
    created_at          timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at          timestamp with time zone,
    CONSTRAINT support_agent_expertise_common_category_id_fkey FOREIGN KEY (common_category_id) REFERENCES common_categories(id),
    CONSTRAINT support_agent_expertise_pkey PRIMARY KEY (id)
);

CREATE INDEX support_agent_expertise_lookup_idx ON public.support_agent_expertises USING btree (common_category_id, is_available);
CREATE UNIQUE INDEX support_agent_expertise_uniq ON public.support_agent_expertises USING btree (iam_user_id, common_category_id) WHERE (deleted_at IS NULL);
