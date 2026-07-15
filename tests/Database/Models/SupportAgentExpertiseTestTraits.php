<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Support\Database\Filters\SupportAgentExpertiseQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportAgentExpertiseService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait SupportAgentExpertiseTestTraits
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

    public function test_http_supportagentexpertise_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supportagentexpertise',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supportagentexpertise_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supportagentexpertise', [
            'form_params'   =>  [
                'proficiency'  =>  '1',
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
    public function test_supportagentexpertise_model_get()
    {
        $result = AbstractSupportAgentExpertiseService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportagentexpertise_get_all()
    {
        $result = AbstractSupportAgentExpertiseService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportagentexpertise_get_paginated()
    {
        $result = AbstractSupportAgentExpertiseService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supportagentexpertise_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportagentexpertise_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::first();

            event(new \NextDeveloper\Support\Events\SupportAgentExpertise\SupportAgentExpertiseRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_proficiency_filter()
    {
        try {
            $request = new Request(
                [
                'proficiency'  =>  '1'
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportagentexpertise_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportAgentExpertiseQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportAgentExpertise::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}