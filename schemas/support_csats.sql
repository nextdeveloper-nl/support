-- PostgreSQL

CREATE TABLE support_csats (
    id                 bigint NOT NULL DEFAULT nextval('support_csat_id_seq'::regclass),
    uuid               uuid DEFAULT gen_random_uuid(),
    support_ticket_id  bigint NOT NULL,
    score              smallint NOT NULL,
    comment            text,
    iam_account_id     bigint,
    iam_user_id        bigint,
    created_at         timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at         timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at         timestamp with time zone,
    CONSTRAINT support_csat_support_ticket_id_fkey FOREIGN KEY (support_ticket_id) REFERENCES support_tickets(id),
    CONSTRAINT support_csat_pkey PRIMARY KEY (id)
);

CREATE INDEX support_csat_ticket_idx ON public.support_csats USING btree (support_ticket_id);
