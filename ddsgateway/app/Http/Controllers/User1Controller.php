<?php

namespace App\Http\Controllers;

//use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Services\User1Service;
use App\Services\User2Service;

class User1Controller extends Controller
{
    // use to add your Traits ApiResponser
    use ApiResponser;
    
    /**
     * The service to consume the User1 Microservice
     * @var User1Service
     */
    public $user1Service;
    public $user2Service;

    /**
     * Create a new controller instance
     * @return void
     */
    public function __construct(User1Service $user1Service, User2Service $user2Service)
    {
        $this->user1Service = $user1Service;
        $this->user2Service = $user2Service;
    }

    /**
     * Return the list of users
     * @return Illuminate\Http\Response
     */
    public function index()
    {
        return $this->successResponse($this->user1Service->obtainUsers1()); 
    }

    //Add
    public function add(Request $request)
    {
        //Validate
        $rules = [
            'username' => 'required|max:20',
            'password' => 'required|max:20',
            'gender'   => 'required|in:Male,Female',
            'jobid'    => 'required|numeric|min:1|not_in:0'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        //Try Site 1 first
        if ($data['jobid'] <= 5) {

            $job = $this->user1Service->obtainUserJob($data['jobid']);

            if (!$job) {
                return $this->errorResponse('Job not found in Site 1', 404);
            }

            $response = $this->user1Service->createUser1($data);

            return $this->successResponse($response, Response::HTTP_CREATED);
        }

        //Alternate or fallback to site 2
        $job = $this->user2Service->obtainUserJob($data['jobid']);

        if (!$job) {
            return $this->errorResponse('Job not found in Site 2', 404);
        }

        //Transform data for Site 2
        $site2Data = [
            'empName' => $data['username'], // mapping
            'password' => $data['password'],
            'gender'   => $data['gender'],
            'jobid'    => $data['jobid'],
        ];

        $response = $this->user2Service->createUser2($site2Data);

        return $this->successResponse($response, Response::HTTP_CREATED);
    }
    
    //Show
     public function show($id)
    {
        return $this->successResponse($this->user1Service->obtainUser1($id));
    }

    //Update
    public function update(Request $request,$id)
    {
        //Validate input
        $rules = [
            'username' => 'required|max:20',
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

            $updated = $this->user1Service->editUser1($data, $id);

            return $this->successResponse($updated);
        }

        //Route to Site 2
        $job = $this->user2Service->obtainUserJob($data['jobid']);

        if (!$job) {
            return $this->errorResponse('Job not found in Site 2', 404);
        }

        //Transform data for Site 2
        $site2Data = [
            'empName' => $data['username'],
            'password' => $data['password'],
            'gender'   => $data['gender'],
            'jobid'    => $data['jobid'],
        ];

        $updated = $this->user2Service->editUser2($site2Data, $id);

        return $this->successResponse($updated);
    }

    //Delete
    public function delete($id)
    {
        return $this->successResponse($this->user1Service->deleteUser1($id));
    }
}