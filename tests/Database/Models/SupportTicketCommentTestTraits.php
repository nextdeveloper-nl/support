<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Support\Database\Filters\SupportTicketCommentQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportTicketCommentService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait SupportTicketCommentTestTraits
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

    public function test_http_supportticketcomment_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supportticketcomment',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supportticketcomment_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supportticketcomment', [
            'form_params'   =>  [
                'comment'  =>  'a',
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
    public function test_supportticketcomment_model_get()
    {
        $result = AbstractSupportTicketCommentService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportticketcomment_get_all()
    {
        $result = AbstractSupportTicketCommentService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportticketcomment_get_paginated()
    {
        $result = AbstractSupportTicketCommentService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supportticketcomment_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportticketcomment_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::first();

            event(new \NextDeveloper\Support\Events\SupportTicketComment\SupportTicketCommentRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_comment_filter()
    {
        try {
            $request = new Request(
                [
                'comment'  =>  'a'
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportticketcomment_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTicketCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTicketComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}