<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Support\Database\Filters\SupportTestQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportTestService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait SupportTestTestTraits
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

    public function test_http_supporttest_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supporttest',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supporttest_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supporttest', [
            'form_params'   =>  [
                'name'  =>  'a',
                'result'  =>  'a',
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
    public function test_supporttest_model_get()
    {
        $result = AbstractSupportTestService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supporttest_get_all()
    {
        $result = AbstractSupportTestService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supporttest_get_paginated()
    {
        $result = AbstractSupportTestService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supporttest_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supporttest_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportTest::first();

            event(new \NextDeveloper\Support\Events\SupportTest\SupportTestRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_result_filter()
    {
        try {
            $request = new Request(
                [
                'result'  =>  'a'
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supporttest_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportTestQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportTest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}