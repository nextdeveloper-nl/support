<?php

namespace NextDeveloper\Support\Http\Controllers\TicketAudits;

use Illuminate\Http\Request;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\Commons\Http\Traits\Addresses;
use NextDeveloper\Commons\Http\Traits\Tags;
use NextDeveloper\Support\Database\Filters\TicketAuditsQueryFilter;
use NextDeveloper\Support\Database\Models\TicketAudits;
use NextDeveloper\Support\Http\Controllers\AbstractController;
use NextDeveloper\Support\Http\Requests\TicketAudits\TicketAuditsCreateRequest;
use NextDeveloper\Support\Http\Requests\TicketAudits\TicketAuditsUpdateRequest;
use NextDeveloper\Support\Services\TicketAuditsService;

class TicketAuditsController extends AbstractController
{
    private $model = TicketAudits::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of ticketaudits.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  TicketAuditsQueryFilter $filter  An object that builds search query
     * @param  Request                 $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TicketAuditsQueryFilter $filter, Request $request)
    {
        $data = TicketAuditsService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $ticketAuditsId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = TicketAuditsService::getByRef($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method returns the list of sub objects the related object. Sub object means an object which is preowned by
     * this object.
     *
     * It can be tags, addresses, states etc.
     *
     * @param  $ref
     * @param  $subObject
     * @return void
     */
    public function relatedObjects($ref, $subObject)
    {
        $objects = TicketAuditsService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created TicketAudits object on database.
     *
     * @param  TicketAuditsCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(TicketAuditsCreateRequest $request)
    {
        $model = TicketAuditsService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates TicketAudits object on database.
     *
     * @param  $ticketAuditsId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($ticketAuditsId, TicketAuditsUpdateRequest $request)
    {
        $model = TicketAuditsService::update($ticketAuditsId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates TicketAudits object on database.
     *
     * @param  $ticketAuditsId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($ticketAuditsId)
    {
        $model = TicketAuditsService::delete($ticketAuditsId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
