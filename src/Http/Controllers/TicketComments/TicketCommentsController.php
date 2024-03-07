<?php

namespace NextDeveloper\Support\Http\Controllers\TicketComments;

use Illuminate\Http\Request;
use NextDeveloper\Support\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\Support\Http\Requests\TicketComments\TicketCommentsUpdateRequest;
use NextDeveloper\Support\Database\Filters\TicketCommentsQueryFilter;
use NextDeveloper\Support\Database\Models\TicketComments;
use NextDeveloper\Support\Services\TicketCommentsService;
use NextDeveloper\Support\Http\Requests\TicketComments\TicketCommentsCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags;use NextDeveloper\Commons\Http\Traits\Addresses;
class TicketCommentsController extends AbstractController
{
    private $model = TicketComments::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of ticketcomments.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  TicketCommentsQueryFilter $filter  An object that builds search query
     * @param  Request                   $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TicketCommentsQueryFilter $filter, Request $request)
    {
        $data = TicketCommentsService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $ticketCommentsId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = TicketCommentsService::getByRef($ref);

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
        $objects = TicketCommentsService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created TicketComments object on database.
     *
     * @param  TicketCommentsCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(TicketCommentsCreateRequest $request)
    {
        $model = TicketCommentsService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates TicketComments object on database.
     *
     * @param  $ticketCommentsId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($ticketCommentsId, TicketCommentsUpdateRequest $request)
    {
        $model = TicketCommentsService::update($ticketCommentsId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates TicketComments object on database.
     *
     * @param  $ticketCommentsId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($ticketCommentsId)
    {
        $model = TicketCommentsService::delete($ticketCommentsId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
