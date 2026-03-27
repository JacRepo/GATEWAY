<?php

namespace App\Http\Controllers;

//use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Services\User2Service;
use App\Services\User1Service;

class User2Controller extends Controller
{
    // use to add your Traits ApiResponser
    use ApiResponser;
    
    /**
     * The service to consume the User1 Microservice
     * @var User2Service
     */
    public $user2Service;
    public $user1Service;

    /**
     * Create a new controller instance
     * @return void
     */
    public function __construct(User2Service $user2Service, User1Service $user1Service)
    {
        $this->user2Service = $user2Service;
        $this->user1Service = $user1Service;
    }

    /**
     * Return the list of users
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse($this->user2Service->obtainUsers2()); 
    }

    //Add
    public function add(Request $request )
    {
        //Validate input
        $rules = [
            'empName' => 'required|max:20',
            'password' => 'required|max:20',
            'gender'   => 'required|in:Male,Female',
            'jobid'    => 'required|numeric|min:1|not_in:0'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        //If jobid belongs to Site 1 -> forward
        if ($data['jobid'] <= 5)
        {
            $job = $this->user1Service->obtainUserJob($data['jobid']);

            if (!$job) {
                return $this->errorResponse('Job not found in Site 1', 404);
            }

            //Transform Site 2 -> Site 1 format
            $site1Data = [
                'username' => $data['empName'],
                'password' => $data['password'],
                'gender'   => $data['gender'],
                'jobid'    => $data['jobid'],
            ];

            return $this->successResponse(
                $this->user1Service->createUser1($site1Data),
                Response::HTTP_CREATED
            );
        }

        //Handle locally in Site 2
        $job = $this->user2Service->obtainUserJob($data['jobid']);

        if (!$job) {
            return $this->errorResponse('Job not found in Site 2', 404);
        }

        return $this->successResponse(
            $this->user2Service->createUser2($data),
            Response::HTTP_CREATED
        );
    }
    
    //Show
     public function show($empID)
    {
        return $this->successResponse($this->user2Service->obtainUser2($empID));
    }

    //Update
    public function update(Request $request,$empID)
    {
        //Validate input (Site2)
        $rules = [
            'empName' => 'required|max:20',
            'password' => 'required|max:20',
            'gender'   => 'required|in:Male,Female',
            'jobid'    => 'required|numeric|min:1|not_in:0'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        //Route to Site 1
        if ($data['jobid'] <= 5)
        {
            $job = $this->user1Service->obtainUserJob($data['jobid']);

            if (!$job) {
                return $this->errorResponse('Job not found in Site 1', 404);
            }

            //Transform site2 to site1
            $site1Data = [
                'username' => $data['empName'],
                'password' => $data['password'],
                'gender'   => $data['gender'],
                'jobid'    => $data['jobid'],
            ];

            $updated = $this->user1Service->editUser1($site1Data, $empID);

            return $this->successResponse($updated);
        }

        //Handle local
        $job = $this->user2Service->obtainUserJob($data['jobid']);

        if (!$job) {
            return $this->errorResponse('Job not found in Site 2', 404);
        }

        // No transformation needed for local update
        $updated = $this->user2Service->editUser2($data, $empID);

        return $this->successResponse($updated);
    }

    //Delete
    public function delete($empID)
    {
        return $this->successResponse($this->user2Service->deleteUser2($empID));
    }
}