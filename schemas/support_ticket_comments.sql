-- PostgreSQL

CREATE TABLE support_ticket_comments (
    id                 bigint NOT NULL DEFAULT nextval('support_comments_id_seq'::regclass),
    uuid               uuid DEFAULT gen_random_uuid(),
    comment            text NOT NULL, -- [ui:markdown]
    iam_account_id     bigint,
    iam_user_id        bigint,
    support_ticket_id  bigint NOT NULL,
    created_at         timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at         timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at         timestamp with time zone,
    is_system_message  boolean DEFAULT false,
    is_internal        boolean NOT NULL DEFAULT false,
    CONSTRAINT support_ticket_comments_pkey PRIMARY KEY (id)
);
