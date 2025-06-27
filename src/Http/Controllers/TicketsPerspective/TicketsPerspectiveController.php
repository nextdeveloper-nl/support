<?php

namespace NextDeveloper\Support\Http\Controllers\TicketsPerspective;

use Illuminate\Http\Request;
use NextDeveloper\Support\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\Support\Http\Requests\TicketsPerspective\TicketsPerspectiveUpdateRequest;
use NextDeveloper\Support\Database\Filters\TicketsPerspectiveQueryFilter;
use NextDeveloper\Support\Database\Models\TicketsPerspective;
use NextDeveloper\Support\Services\TicketsPerspectiveService;
use NextDeveloper\Support\Http\Requests\TicketsPerspective\TicketsPerspectiveCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class TicketsPerspectiveController extends AbstractController
{
    private $model = TicketsPerspective::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of ticketsperspectives.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  TicketsPerspectiveQueryFilter $filter  An object that builds search query
     * @param  Request                       $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TicketsPerspectiveQueryFilter $filter, Request $request)
    {
        $data = TicketsPerspectiveService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = TicketsPerspectiveService::getActions();

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * Makes the related action to the object
     *
     * @param  $objectId
     * @param  $action
     * @return array
     */
    public function doAction($objectId, $action)
    {
        $actionId = TicketsPerspectiveService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $ticketsPerspectiveId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = TicketsPerspectiveService::getByRef($ref);

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
        $objects = TicketsPerspectiveService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created TicketsPerspective object on database.
     *
     * @param  TicketsPerspectiveCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(TicketsPerspectiveCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = TicketsPerspectiveService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates TicketsPerspective object on database.
     *
     * @param  $ticketsPerspectiveId
     * @param  TicketsPerspectiveUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($ticketsPerspectiveId, TicketsPerspectiveUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = TicketsPerspectiveService::update($ticketsPerspectiveId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates TicketsPerspective object on database.
     *
     * @param  $ticketsPerspectiveId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($ticketsPerspectiveId)
    {
        $model = TicketsPerspectiveService::delete($ticketsPerspectiveId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
