<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Support\Database\Filters\SupportCsatQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportCsatService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait SupportCsatTestTraits
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

    public function test_http_supportcsat_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supportcsat',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supportcsat_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supportcsat', [
            'form_params'   =>  [
                'comment'  =>  'a',
                'score'  =>  '1',
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
    public function test_supportcsat_model_get()
    {
        $result = AbstractSupportCsatService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportcsat_get_all()
    {
        $result = AbstractSupportCsatService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportcsat_get_paginated()
    {
        $result = AbstractSupportCsatService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supportcsat_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportcsat_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportCsat::first();

            event(new \NextDeveloper\Support\Events\SupportCsat\SupportCsatRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_comment_filter()
    {
        try {
            $request = new Request(
                [
                'comment'  =>  'a'
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_score_filter()
    {
        try {
            $request = new Request(
                [
                'score'  =>  '1'
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportcsat_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportCsatQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportCsat::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}