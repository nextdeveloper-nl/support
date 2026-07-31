-- PostgreSQL

CREATE TABLE support_tickets (
    id                           bigint NOT NULL DEFAULT nextval('support_tickets_id_seq'::regclass),
    uuid                         uuid DEFAULT gen_random_uuid(),
    title                        text NOT NULL,
    description                  text, -- [ui:markdown]
    iam_account_id               bigint,
    iam_user_id                  bigint,
    tags                         text[] NOT NULL DEFAULT '{}'::text[],
    is_closed                    boolean DEFAULT false, -- [ro]
    level                        smallint DEFAULT 1, -- [ro]
    priority                     smallint DEFAULT 1, -- [ro]
    response_time                timestamp with time zone, -- [ro]
    object_id                    bigint, -- [ro]
    object_type                  text, -- [ro]
    created_at                   timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at                   timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at                   timestamp with time zone,
    is_public                    boolean DEFAULT false,
    responsible_user_id          bigint, -- [alias:iam_user_id]
    time_spent                   integer, -- time spent in terms of minutes
    watcher_user_ids             bigint[],
    watcher_account_ids          bigint[],
    support_seeker_account_id    bigint, -- [alias:iam_account_id]
    status                       text NOT NULL DEFAULT 'open'::text,
    common_category_id           bigint,
    first_response_at            timestamp with time zone,
    resolved_at                  timestamp with time zone,
    reopened_count               integer NOT NULL DEFAULT 0,
    is_first_contact_resolution  boolean NOT NULL DEFAULT false,
    sla_resolution_due_at        timestamp with time zone,
    sla_response_breached        boolean NOT NULL DEFAULT false,
    sla_resolution_breached      boolean NOT NULL DEFAULT false,
    CONSTRAINT support_tickets_status_chk CHECK ((status = ANY (ARRAY['open'::text, 'pending'::text, 'waiting_on_customer'::text, 'resolved'::text, 'closed'::text]))),
    CONSTRAINT support_tickets_common_category_id_fk FOREIGN KEY (common_category_id) REFERENCES common_categories(id),
    CONSTRAINT support_tickets_pkey PRIMARY KEY (id)
);

CREATE INDEX support_tickets_common_category_idx ON public.support_tickets USING btree (common_category_id);
CREATE INDEX support_tickets_responsible_user_idx ON public.support_tickets USING btree (responsible_user_id);
CREATE INDEX support_tickets_sla_due_open_idx ON public.support_tickets USING btree (sla_resolution_due_at) WHERE ((deleted_at IS NULL) AND (status <> ALL (ARRAY['resolved'::text, 'closed'::text])));
CREATE INDEX support_tickets_status_idx ON public.support_tickets USING btree (status);
