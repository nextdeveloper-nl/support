-- PostgreSQL

CREATE TABLE support_ticket_audits (
    id           bigint NOT NULL DEFAULT nextval('support_ticket_audits_id_seq'::regclass),
    uuid         uuid DEFAULT gen_random_uuid(),
    comments     text, -- [ui:markdown]
    iam_user_id  bigint,
    point        smallint DEFAULT 0,
    created_at   timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at   timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at   timestamp with time zone,
    CONSTRAINT support_ticket_audits_pkey PRIMARY KEY (id)
);
