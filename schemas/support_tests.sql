-- PostgreSQL

CREATE TABLE support_tests (
    id                 bigint NOT NULL DEFAULT nextval('support_tests_id_seq'::regclass),
    uuid               uuid DEFAULT gen_random_uuid(),
    name               text NOT NULL,
    result             text NOT NULL, -- [ui:markdown]
    data               json NOT NULL,
    is_passed          boolean DEFAULT true,
    support_ticket_id  bigint NOT NULL,
    common_action_id   bigint NOT NULL,
    created_at         timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at         timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at         timestamp with time zone,
    CONSTRAINT support_tests_pkey PRIMARY KEY (id)
);
