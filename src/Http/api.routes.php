<?php

Route::prefix('support')->group(
    function () {
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

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
    }
);
