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
