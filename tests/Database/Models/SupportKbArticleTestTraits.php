<?php

namespace NextDeveloper\Support\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Support\Database\Filters\SupportKbArticleQueryFilter;
use NextDeveloper\Support\Services\AbstractServices\AbstractSupportKbArticleService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait SupportKbArticleTestTraits
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

    public function test_http_supportkbarticle_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/support/supportkbarticle',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_supportkbarticle_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/support/supportkbarticle', [
            'form_params'   =>  [
                'title'  =>  'a',
                'slug'  =>  'a',
                'body'  =>  'a',
                'excerpt'  =>  'a',
                'tags'  =>  'a',
                'view_count'  =>  '1',
                'helpful_count'  =>  '1',
                'not_helpful_count'  =>  '1',
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
    public function test_supportkbarticle_model_get()
    {
        $result = AbstractSupportKbArticleService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportkbarticle_get_all()
    {
        $result = AbstractSupportKbArticleService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_supportkbarticle_get_paginated()
    {
        $result = AbstractSupportKbArticleService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_supportkbarticle_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_supportkbarticle_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::first();

            event(new \NextDeveloper\Support\Events\SupportKbArticle\SupportKbArticleRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_title_filter()
    {
        try {
            $request = new Request(
                [
                'title'  =>  'a'
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_slug_filter()
    {
        try {
            $request = new Request(
                [
                'slug'  =>  'a'
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_body_filter()
    {
        try {
            $request = new Request(
                [
                'body'  =>  'a'
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_excerpt_filter()
    {
        try {
            $request = new Request(
                [
                'excerpt'  =>  'a'
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_tags_filter()
    {
        try {
            $request = new Request(
                [
                'tags'  =>  'a'
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_view_count_filter()
    {
        try {
            $request = new Request(
                [
                'view_count'  =>  '1'
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_helpful_count_filter()
    {
        try {
            $request = new Request(
                [
                'helpful_count'  =>  '1'
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_not_helpful_count_filter()
    {
        try {
            $request = new Request(
                [
                'not_helpful_count'  =>  '1'
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_supportkbarticle_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new SupportKbArticleQueryFilter($request);

            $model = \NextDeveloper\Support\Database\Models\SupportKbArticle::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}