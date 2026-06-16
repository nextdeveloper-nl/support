<?php

Route::prefix('support')->group(
    function () {
        Route::prefix('tests')->group(
            function () {
                Route::get('/', 'Tests\TestsController@index');
                Route::get('/actions', 'Tests\TestsController@getActions');

                Route::get('{support_tests}/tags ', 'Tests\TestsController@tags');
                Route::post('{support_tests}/tags ', 'Tests\TestsController@saveTags');
                Route::get('{support_tests}/addresses ', 'Tests\TestsController@addresses');
                Route::post('{support_tests}/addresses ', 'Tests\TestsController@saveAddresses');

                Route::get('/{support_tests}/{subObjects}', 'Tests\TestsController@relatedObjects');
                Route::get('/{support_tests}', 'Tests\TestsController@show');

                Route::post('/', 'Tests\TestsController@store');
                Route::post('/{support_tests}/do/{action}', 'Tests\TestsController@doAction');

                Route::patch('/{support_tests}', 'Tests\TestsController@update');
                Route::delete('/{support_tests}', 'Tests\TestsController@destroy');
            }
        );

        Route::prefix('ticket-audits')->group(
            function () {
                Route::get('/', 'TicketAudits\TicketAuditsController@index');
                Route::get('/actions', 'TicketAudits\TicketAuditsController@getActions');

                Route::get('{support_ticket_audits}/tags ', 'TicketAudits\TicketAuditsController@tags');
                Route::post('{support_ticket_audits}/tags ', 'TicketAudits\TicketAuditsController@saveTags');
                Route::get('{support_ticket_audits}/addresses ', 'TicketAudits\TicketAuditsController@addresses');
                Route::post('{support_ticket_audits}/addresses ', 'TicketAudits\TicketAuditsController@saveAddresses');

                Route::get('/{support_ticket_audits}/{subObjects}', 'TicketAudits\TicketAuditsController@relatedObjects');
                Route::get('/{support_ticket_audits}', 'TicketAudits\TicketAuditsController@show');

                Route::post('/', 'TicketAudits\TicketAuditsController@store');
                Route::post('/{support_ticket_audits}/do/{action}', 'TicketAudits\TicketAuditsController@doAction');

                Route::patch('/{support_ticket_audits}', 'TicketAudits\TicketAuditsController@update');
                Route::delete('/{support_ticket_audits}', 'TicketAudits\TicketAuditsController@destroy');
            }
        );

        Route::prefix('tickets')->group(
            function () {
                Route::get('/', 'Tickets\TicketsController@index');
                Route::get('/actions', 'Tickets\TicketsController@getActions');

                Route::get('{support_tickets}/tags ', 'Tickets\TicketsController@tags');
                Route::post('{support_tickets}/tags ', 'Tickets\TicketsController@saveTags');
                Route::get('{support_tickets}/addresses ', 'Tickets\TicketsController@addresses');
                Route::post('{support_tickets}/addresses ', 'Tickets\TicketsController@saveAddresses');

                Route::get('/{support_tickets}/{subObjects}', 'Tickets\TicketsController@relatedObjects');
                Route::get('/{support_tickets}', 'Tickets\TicketsController@show');

                Route::post('/', 'Tickets\TicketsController@store');
                Route::post('/{support_tickets}/do/{action}', 'Tickets\TicketsController@doAction');

                Route::patch('/{support_tickets}', 'Tickets\TicketsController@update');
                Route::delete('/{support_tickets}', 'Tickets\TicketsController@destroy');
            }
        );

        Route::prefix('ticket-comments')->group(
            function () {
                Route::get('/', 'TicketComments\TicketCommentsController@index');
                Route::get('/actions', 'TicketComments\TicketCommentsController@getActions');

                Route::get('{support_ticket_comments}/tags ', 'TicketComments\TicketCommentsController@tags');
                Route::post('{support_ticket_comments}/tags ', 'TicketComments\TicketCommentsController@saveTags');
                Route::get('{support_ticket_comments}/addresses ', 'TicketComments\TicketCommentsController@addresses');
                Route::post('{support_ticket_comments}/addresses ', 'TicketComments\TicketCommentsController@saveAddresses');

                Route::get('/{support_ticket_comments}/{subObjects}', 'TicketComments\TicketCommentsController@relatedObjects');
                Route::get('/{support_ticket_comments}', 'TicketComments\TicketCommentsController@show');

                Route::post('/', 'TicketComments\TicketCommentsController@store');
                Route::post('/{support_ticket_comments}/do/{action}', 'TicketComments\TicketCommentsController@doAction');

                Route::patch('/{support_ticket_comments}', 'TicketComments\TicketCommentsController@update');
                Route::delete('/{support_ticket_comments}', 'TicketComments\TicketCommentsController@destroy');
            }
        );

        Route::prefix('kb-articles')->group(
            function () {
                Route::get('/', 'KbArticles\KbArticlesController@index');
                Route::get('/actions', 'KbArticles\KbArticlesController@getActions');

                Route::get('{support_kb_articles}/tags ', 'KbArticles\KbArticlesController@tags');
                Route::post('{support_kb_articles}/tags ', 'KbArticles\KbArticlesController@saveTags');
                Route::get('{support_kb_articles}/addresses ', 'KbArticles\KbArticlesController@addresses');
                Route::post('{support_kb_articles}/addresses ', 'KbArticles\KbArticlesController@saveAddresses');

                Route::get('/{support_kb_articles}/{subObjects}', 'KbArticles\KbArticlesController@relatedObjects');
                Route::get('/{support_kb_articles}', 'KbArticles\KbArticlesController@show');

                Route::post('/', 'KbArticles\KbArticlesController@store');
                Route::post('/{support_kb_articles}/do/{action}', 'KbArticles\KbArticlesController@doAction');

                Route::patch('/{support_kb_articles}', 'KbArticles\KbArticlesController@update');
                Route::delete('/{support_kb_articles}', 'KbArticles\KbArticlesController@destroy');
            }
        );

        Route::prefix('sla-policies')->group(
            function () {
                Route::get('/', 'SlaPolicies\SlaPoliciesController@index');
                Route::get('/actions', 'SlaPolicies\SlaPoliciesController@getActions');

                Route::get('{support_sla_policies}/tags ', 'SlaPolicies\SlaPoliciesController@tags');
                Route::post('{support_sla_policies}/tags ', 'SlaPolicies\SlaPoliciesController@saveTags');
                Route::get('{support_sla_policies}/addresses ', 'SlaPolicies\SlaPoliciesController@addresses');
                Route::post('{support_sla_policies}/addresses ', 'SlaPolicies\SlaPoliciesController@saveAddresses');

                Route::get('/{support_sla_policies}/{subObjects}', 'SlaPolicies\SlaPoliciesController@relatedObjects');
                Route::get('/{support_sla_policies}', 'SlaPolicies\SlaPoliciesController@show');

                Route::post('/', 'SlaPolicies\SlaPoliciesController@store');
                Route::post('/{support_sla_policies}/do/{action}', 'SlaPolicies\SlaPoliciesController@doAction');

                Route::patch('/{support_sla_policies}', 'SlaPolicies\SlaPoliciesController@update');
                Route::delete('/{support_sla_policies}', 'SlaPolicies\SlaPoliciesController@destroy');
            }
        );

        Route::prefix('agent-expertise')->group(
            function () {
                Route::get('/', 'AgentExpertise\AgentExpertiseController@index');
                Route::get('/actions', 'AgentExpertise\AgentExpertiseController@getActions');

                Route::get('{support_agent_expertise}/tags ', 'AgentExpertise\AgentExpertiseController@tags');
                Route::post('{support_agent_expertise}/tags ', 'AgentExpertise\AgentExpertiseController@saveTags');
                Route::get('{support_agent_expertise}/addresses ', 'AgentExpertise\AgentExpertiseController@addresses');
                Route::post('{support_agent_expertise}/addresses ', 'AgentExpertise\AgentExpertiseController@saveAddresses');

                Route::get('/{support_agent_expertise}/{subObjects}', 'AgentExpertise\AgentExpertiseController@relatedObjects');
                Route::get('/{support_agent_expertise}', 'AgentExpertise\AgentExpertiseController@show');

                Route::post('/', 'AgentExpertise\AgentExpertiseController@store');
                Route::post('/{support_agent_expertise}/do/{action}', 'AgentExpertise\AgentExpertiseController@doAction');

                Route::patch('/{support_agent_expertise}', 'AgentExpertise\AgentExpertiseController@update');
                Route::delete('/{support_agent_expertise}', 'AgentExpertise\AgentExpertiseController@destroy');
            }
        );

        Route::prefix('csat')->group(
            function () {
                Route::get('/', 'Csat\CsatController@index');
                Route::get('/actions', 'Csat\CsatController@getActions');

                Route::get('{support_csat}/tags ', 'Csat\CsatController@tags');
                Route::post('{support_csat}/tags ', 'Csat\CsatController@saveTags');
                Route::get('{support_csat}/addresses ', 'Csat\CsatController@addresses');
                Route::post('{support_csat}/addresses ', 'Csat\CsatController@saveAddresses');

                Route::get('/{support_csat}/{subObjects}', 'Csat\CsatController@relatedObjects');
                Route::get('/{support_csat}', 'Csat\CsatController@show');

                Route::post('/', 'Csat\CsatController@store');
                Route::post('/{support_csat}/do/{action}', 'Csat\CsatController@doAction');

                Route::patch('/{support_csat}', 'Csat\CsatController@update');
                Route::delete('/{support_csat}', 'Csat\CsatController@destroy');
            }
        );

        Route::prefix('ticket-comments-perspective')->group(
            function () {
                Route::get('/', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@index');
                Route::get('/actions', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@getActions');

                Route::get('{stcp}/tags ', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@tags');
                Route::post('{stcp}/tags ', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@saveTags');
                Route::get('{stcp}/addresses ', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@addresses');
                Route::post('{stcp}/addresses ', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@saveAddresses');

                Route::get('/{stcp}/{subObjects}', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@relatedObjects');
                Route::get('/{stcp}', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@show');

                Route::post('/', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@store');
                Route::post('/{stcp}/do/{action}', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@doAction');

                Route::patch('/{stcp}', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@update');
                Route::delete('/{stcp}', 'TicketCommentsPerspective\TicketCommentsPerspectiveController@destroy');
            }
        );

        Route::prefix('tickets-perspective')->group(
            function () {
                Route::get('/', 'TicketsPerspective\TicketsPerspectiveController@index');
                Route::get('/actions', 'TicketsPerspective\TicketsPerspectiveController@getActions');

                Route::get('{support_tickets_perspective}/tags ', 'TicketsPerspective\TicketsPerspectiveController@tags');
                Route::post('{support_tickets_perspective}/tags ', 'TicketsPerspective\TicketsPerspectiveController@saveTags');
                Route::get('{support_tickets_perspective}/addresses ', 'TicketsPerspective\TicketsPerspectiveController@addresses');
                Route::post('{support_tickets_perspective}/addresses ', 'TicketsPerspective\TicketsPerspectiveController@saveAddresses');

                Route::get('/{support_tickets_perspective}/{subObjects}', 'TicketsPerspective\TicketsPerspectiveController@relatedObjects');
                Route::get('/{support_tickets_perspective}', 'TicketsPerspective\TicketsPerspectiveController@show');

                Route::post('/', 'TicketsPerspective\TicketsPerspectiveController@store');
                Route::post('/{support_tickets_perspective}/do/{action}', 'TicketsPerspective\TicketsPerspectiveController@doAction');

                Route::patch('/{support_tickets_perspective}', 'TicketsPerspective\TicketsPerspectiveController@update');
                Route::delete('/{support_tickets_perspective}', 'TicketsPerspective\TicketsPerspectiveController@destroy');
            }
        );

        Route::prefix('kb-articles-perspective')->group(
            function () {
                Route::get('/', 'KbArticlesPerspective\KbArticlesPerspectiveController@index');
                Route::get('/actions', 'KbArticlesPerspective\KbArticlesPerspectiveController@getActions');

                Route::get('{support_kb_articles_perspective}/tags ', 'KbArticlesPerspective\KbArticlesPerspectiveController@tags');
                Route::post('{support_kb_articles_perspective}/tags ', 'KbArticlesPerspective\KbArticlesPerspectiveController@saveTags');
                Route::get('{support_kb_articles_perspective}/addresses ', 'KbArticlesPerspective\KbArticlesPerspectiveController@addresses');
                Route::post('{support_kb_articles_perspective}/addresses ', 'KbArticlesPerspective\KbArticlesPerspectiveController@saveAddresses');

                Route::get('/{support_kb_articles_perspective}/{subObjects}', 'KbArticlesPerspective\KbArticlesPerspectiveController@relatedObjects');
                Route::get('/{support_kb_articles_perspective}', 'KbArticlesPerspective\KbArticlesPerspectiveController@show');

                Route::post('/', 'KbArticlesPerspective\KbArticlesPerspectiveController@store');
                Route::post('/{support_kb_articles_perspective}/do/{action}', 'KbArticlesPerspective\KbArticlesPerspectiveController@doAction');

                Route::patch('/{support_kb_articles_perspective}', 'KbArticlesPerspective\KbArticlesPerspectiveController@update');
                Route::delete('/{support_kb_articles_perspective}', 'KbArticlesPerspective\KbArticlesPerspectiveController@destroy');
            }
        );

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE





































    }
);






