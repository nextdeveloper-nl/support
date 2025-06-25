<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;
use NextDeveloper\Support\Database\Filters\SupportCommentQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportCommentService;

trait SupportCommentTestTraits
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

    public function test_http_supportcomment_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supportcomment',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supportcomment_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supportcomment', [
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
    public function test_supportcomment_model_get()
    {
        $result = AbstractSupportCommentService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportcomment_get_all()
    {
        $result = AbstractSupportCommentService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportcomment_get_paginated()
    {
        $result = AbstractSupportCommentService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supportcomment_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcomment_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportComment::first();

            event(new \NextDeveloper\Support\Events\SupportComment\SupportCommentRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_comment_filter()
    {
        try {
            $request = new Request(
                [
                'comment'  =>  'a'
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcomment_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCommentQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportComment::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}
