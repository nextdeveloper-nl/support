<?php

Route::prefix('support')->group(function () {
    Route::prefix('tickets')->group(
        function () {
            Route::get('/', 'Tickets\TicketsController@index');

            Route::get('{support_tickets}/tags ', 'Tickets\TicketsController@tags');
            Route::post('{support_tickets}/tags ', 'Tickets\TicketsController@saveTags');
            Route::get('{support_tickets}/addresses ', 'Tickets\TicketsController@addresses');
            Route::post('{support_tickets}/addresses ', 'Tickets\TicketsController@saveAddresses');

            Route::get('/{support_tickets}/{subObjects}', 'Tickets\TicketsController@relatedObjects');
            Route::get('/{support_tickets}', 'Tickets\TicketsController@show');

            Route::post('/', 'Tickets\TicketsController@store');
            Route::patch('/{support_tickets}', 'Tickets\TicketsController@update');
            Route::delete('/{support_tickets}', 'Tickets\TicketsController@destroy');
        }
    );

    Route::prefix('ticket-audits')->group(
        function () {
            Route::get('/', 'TicketAudits\TicketAuditsController@index');

            Route::get('{support_ticket_audits}/tags ', 'TicketAudits\TicketAuditsController@tags');
            Route::post('{support_ticket_audits}/tags ', 'TicketAudits\TicketAuditsController@saveTags');
            Route::get('{support_ticket_audits}/addresses ', 'TicketAudits\TicketAuditsController@addresses');
            Route::post('{support_ticket_audits}/addresses ', 'TicketAudits\TicketAuditsController@saveAddresses');

            Route::get('/{support_ticket_audits}/{subObjects}', 'TicketAudits\TicketAuditsController@relatedObjects');
            Route::get('/{support_ticket_audits}', 'TicketAudits\TicketAuditsController@show');

            Route::post('/', 'TicketAudits\TicketAuditsController@store');
            Route::patch('/{support_ticket_audits}', 'TicketAudits\TicketAuditsController@update');
            Route::delete('/{support_ticket_audits}', 'TicketAudits\TicketAuditsController@destroy');
        }
    );

    Route::prefix('comments')->group(
        function () {
            Route::get('/', 'Comments\CommentsController@index');

            Route::get('{support_comments}/tags ', 'Comments\CommentsController@tags');
            Route::post('{support_comments}/tags ', 'Comments\CommentsController@saveTags');
            Route::get('{support_comments}/addresses ', 'Comments\CommentsController@addresses');
            Route::post('{support_comments}/addresses ', 'Comments\CommentsController@saveAddresses');

            Route::get('/{support_comments}/{subObjects}', 'Comments\CommentsController@relatedObjects');
            Route::get('/{support_comments}', 'Comments\CommentsController@show');

            Route::post('/', 'Comments\CommentsController@store');
            Route::patch('/{support_comments}', 'Comments\CommentsController@update');
            Route::delete('/{support_comments}', 'Comments\CommentsController@destroy');
        }
    );

    Route::prefix('tests')->group(
        function () {
            Route::get('/', 'Tests\TestsController@index');

            Route::get('{support_tests}/tags ', 'Tests\TestsController@tags');
            Route::post('{support_tests}/tags ', 'Tests\TestsController@saveTags');
            Route::get('{support_tests}/addresses ', 'Tests\TestsController@addresses');
            Route::post('{support_tests}/addresses ', 'Tests\TestsController@saveAddresses');

            Route::get('/{support_tests}/{subObjects}', 'Tests\TestsController@relatedObjects');
            Route::get('/{support_tests}', 'Tests\TestsController@show');

            Route::post('/', 'Tests\TestsController@store');
            Route::patch('/{support_tests}', 'Tests\TestsController@update');
            Route::delete('/{support_tests}', 'Tests\TestsController@destroy');
        }
    );

// EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
});
