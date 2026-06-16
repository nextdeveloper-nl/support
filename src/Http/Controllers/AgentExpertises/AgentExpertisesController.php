<?php

namespace NextDeveloper\Support\Http\Controllers\AgentExpertises;

use Illuminate\Http\Request;
use NextDeveloper\Support\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\Support\Http\Requests\AgentExpertises\AgentExpertisesUpdateRequest;
use NextDeveloper\Support\Database\Filters\AgentExpertisesQueryFilter;
use NextDeveloper\Support\Database\Models\AgentExpertises;
use NextDeveloper\Support\Services\AgentExpertisesService;
use NextDeveloper\Support\Http\Requests\AgentExpertises\AgentExpertisesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class AgentExpertisesController extends AbstractController
{
    private $model = AgentExpertises::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of agentexpertises.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  AgentExpertisesQueryFilter $filter  An object that builds search query
     * @param  Request                    $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(AgentExpertisesQueryFilter $filter, Request $request)
    {
        $data = AgentExpertisesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = AgentExpertisesService::getActions();

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
        $actionId = AgentExpertisesService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $agentExpertisesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = AgentExpertisesService::getByRef($ref);

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
        $objects = AgentExpertisesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created AgentExpertises object on database.
     *
     * @param  AgentExpertisesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(AgentExpertisesCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AgentExpertisesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AgentExpertises object on database.
     *
     * @param  $agentExpertisesId
     * @param  AgentExpertisesUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($agentExpertisesId, AgentExpertisesUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = AgentExpertisesService::update($agentExpertisesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates AgentExpertises object on database.
     *
     * @param  $agentExpertisesId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($agentExpertisesId)
    {
        $model = AgentExpertisesService::delete($agentExpertisesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
