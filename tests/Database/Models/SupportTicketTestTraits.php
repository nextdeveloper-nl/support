<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;
use NextDeveloper\Support\Database\Filters\SupportTicketQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportTicketService;
use Tests\TestCase;

trait SupportTicketTestTraits
{
    public $http;

    /**
     *   Creating the Guzzle object
     */
    public function setupGuzzle()
    {
        $this->http = new Client(
            [
            'base_uri'  =>  '127.0.0.1:8000'
            ]
        );
    }

    /**
     *   Destroying the Guzzle object
     */
    public function destroyGuzzle()
    {
        $this->http = null;
    }

    public function test_http_supportticket_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supportticket',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supportticket_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supportticket', [
            'form_params'   =>  [
                'title'  =>  'a',
                'description'  =>  'a',
                'object_type'  =>  'a',
                'level'  =>  '1',
                'priority'  =>  '1',
                'time_spent'  =>  '1',
                    'response_time'  =>  now(),
                            ],
                ['http_errors' => false]
            ]
        );

        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * Get test
     *
     * @return bool
     */
    public function test_supportticket_model_get()
    {
        $result = AbstractSupportTicketService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportticket_get_all()
    {
        $result = AbstractSupportTicketService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportticket_get_paginated()
    {
        $result = AbstractSupportTicketService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supportticket_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticket_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicket::first();

            event(new \NextDeveloper\Support\Events\SupportTicket\SupportTicketRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_title_filter()
    {
        try {
            $request = new Request(
                [
                'title'  =>  'a'
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_description_filter()
    {
        try {
            $request = new Request(
                [
                'description'  =>  'a'
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_object_type_filter()
    {
        try {
            $request = new Request(
                [
                'object_type'  =>  'a'
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_level_filter()
    {
        try {
            $request = new Request(
                [
                'level'  =>  '1'
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_priority_filter()
    {
        try {
            $request = new Request(
                [
                'priority'  =>  '1'
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_time_spent_filter()
    {
        try {
            $request = new Request(
                [
                'time_spent'  =>  '1'
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_response_time_filter_start()
    {
        try {
            $request = new Request(
                [
                'response_timeStart'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_response_time_filter_end()
    {
        try {
            $request = new Request(
                [
                'response_timeEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_response_time_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'response_timeStart'  =>  now(),
                'response_timeEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticket_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicket::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}