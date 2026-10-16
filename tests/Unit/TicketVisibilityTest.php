<?php

namespace NextDeveloper\Support\Tests\Unit;

use Illuminate\Container\Container;
use Illuminate\Contracts\Routing\ResponseFactory as ResponseFactoryContract;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Routing\Redirector;
use Illuminate\Routing\ResponseFactory;
use NextDeveloper\Support\Services\TicketsService;
use PHPUnit\Framework\TestCase;

/**
 * A write on a ticket the caller cannot see answers 404, as a read of it does (fixlean #614): a
 * workflow action answered 200 with "Ticket not found." inside, and a comment failed with a 500.
 * Ids that cannot be a ticket's are refused before any query; the scoped lookup itself is the
 * generated one.
 */
class TicketVisibilityTest extends TestCase
{
    private ?Container $previous = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->previous = Container::getInstance();

        $app = new Container();
        $app->instance(ResponseFactoryContract::class, new ResponseFactory(
            $this->createMock(ViewFactory::class),
            $this->createMock(Redirector::class)
        ));

        Container::setInstance($app);
    }

    protected function tearDown(): void
    {
        Container::setInstance($this->previous);

        parent::tearDown();
    }

    private function refusal(callable $write): array
    {
        try {
            $write();
        } catch (HttpResponseException $e) {
            return [$e->getResponse()->getStatusCode(), $e->getResponse()->getData(true)];
        }

        $this->fail('The write was not refused.');
    }

    public function test_an_unknown_ticket_is_not_found(): void
    {
        [$status, $body] = $this->refusal(fn () => TicketsService::visibleOrNotFound('PANEL-ERR-000488'));

        $this->assertSame(404, $status);
        $this->assertSame(['error' => ['status' => 404, 'code' => 'ERROR-NOT-FOUND', 'message' => 'Ticket not found.']], $body);
        $this->assertSame(404, $this->refusal(fn () => TicketsService::visibleOrNotFound(null))[0]);
    }

    public function test_a_workflow_action_on_it_is_not_found_instead_of_200_with_an_error(): void
    {
        $this->assertSame(404, $this->refusal(fn () => TicketsService::doAction('PANEL-ERR-000488', 'assign-ticket', ['iam_user_id' => null]))[0]);
        $this->assertSame(404, $this->refusal(fn () => TicketsService::doAction('not-a-ticket', 'change-status', ['status' => 'closed']))[0]);
    }
}
