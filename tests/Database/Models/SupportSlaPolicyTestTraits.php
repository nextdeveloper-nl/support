<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Support\Database\Filters\SupportSlaPolicyQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportSlaPolicyService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait SupportSlaPolicyTestTraits
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

    public function test_http_supportslapolicy_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supportslapolicy',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supportslapolicy_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supportslapolicy', [
            'form_params'   =>  [
                'name'  =>  'a',
                'priority'  =>  '1',
                'response_target_minutes'  =>  '1',
                'resolution_target_minutes'  =>  '1',
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
    public function test_supportslapolicy_model_get()
    {
        $result = AbstractSupportSlaPolicyService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportslapolicy_get_all()
    {
        $result = AbstractSupportSlaPolicyService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportslapolicy_get_paginated()
    {
        $result = AbstractSupportSlaPolicyService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supportslapolicy_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicySavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicySavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicySavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicySavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportslapolicy_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::first();

            event(new \NextDeveloper\Support\Events\SupportSlaPolicy\SupportSlaPolicyRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_name_filter()
    {
        try {
            $request = new Request(
                [
                'name'  =>  'a'
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_priority_filter()
    {
        try {
            $request = new Request(
                [
                'priority'  =>  '1'
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_response_target_minutes_filter()
    {
        try {
            $request = new Request(
                [
                'response_target_minutes'  =>  '1'
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_resolution_target_minutes_filter()
    {
        try {
            $request = new Request(
                [
                'resolution_target_minutes'  =>  '1'
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportslapolicy_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportSlaPolicyQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportSlaPolicy::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}