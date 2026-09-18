<?php

return [
    /*
     * Scopes every support model registers. Without the authorization scope a caller with read
     * permission sees the tickets, comments and audits of every account; an application that
     * publishes its own support.php replaces this key.
     */
    'scopes' => [
        'global' => [
            '\NextDeveloper\IAM\Database\Scopes\AuthorizationScope',
        ],
    ],

    'workflow' => [
        /*
         * Roles that work tickets as agents in TicketWorkflowService: they may move a ticket through
         * the whole workflow (pending, waiting on customer, resolved, re-open). Anyone else may only
         * close their own ticket.
         */
        'agent_roles' => ['support-admin', 'support-specialist'],
    ],
];
