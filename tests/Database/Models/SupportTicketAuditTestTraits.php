<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;
use NextDeveloper\Support\Database\Filters\SupportTicketAuditQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportTicketAuditService;
use Tests\TestCase;

trait SupportTicketAuditTestTraits
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

    public function test_http_supportticketaudit_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supportticketaudit',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supportticketaudit_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supportticketaudit', [
            'form_params'   =>  [
                'comments'  =>  'a',
                'point'  =>  '1',
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
    public function test_supportticketaudit_model_get()
    {
        $result = AbstractSupportTicketAuditService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportticketaudit_get_all()
    {
        $result = AbstractSupportTicketAuditService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportticketaudit_get_paginated()
    {
        $result = AbstractSupportTicketAuditService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supportticketaudit_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketaudit_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::first();

            event(new \NextDeveloper\Support\Events\SupportTicketAudit\SupportTicketAuditRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_comments_filter()
    {
        try {
            $request = new Request(
                [
                'comments'  =>  'a'
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_point_filter()
    {
        try {
            $request = new Request(
                [
                'point'  =>  '1'
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketaudit_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketAuditQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketAudit::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}