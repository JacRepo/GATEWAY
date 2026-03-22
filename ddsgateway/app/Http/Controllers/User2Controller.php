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
        if ($request->jobid <= 5)
        {
            // Redirect to Site 1
            $job = $this->user1Service->obtainUserJob($request->jobid);

            if (!$job) {
                return $this->errorResponse('Job not found in Site 1', 404);
            }

            return $this->successResponse(
                $this->user1Service->createUser1($request->all()),
                Response::HTTP_CREATED
            );
        } 
        else // 6–10
        {
            // Handle locally in Site 2
            $job = $this->user2Service->obtainUserJob($request->jobid);

            if (!$job) {
                return $this->errorResponse('Job not found in Site 2', 404);
            }

            return $this->successResponse(
                $this->user2Service->createUser2($request->all()),
                Response::HTTP_CREATED
            );
        }
    }
    
    //Show
     public function show($id)
    {
        return $this->successResponse($this->user2Service->obtainUser2($id));
    }

    //Update
      public function update(Request $request,$id)
    {
        if ($request->jobid <= 5)
        {
            $job = $this->user1Service->obtainUserJob($request->jobid);

            if (!$job) {
                return $this->errorResponse('Job not found in Site 1', 404);
            }

            return $this->successResponse(
                $this->user1Service->editUser1($request->all(), $id)
            );
        } 
        else 
        {
            $job = $this->user2Service->obtainUserJob($request->jobid);

            if (!$job) {
                return $this->errorResponse('Job not found in Site 2', 404);
            }

            return $this->successResponse(
                $this->user2Service->editUser2($request->all(), $id)
            );
        }
    }

    //Delete
    public function delete($id)
    {
        return $this->successResponse($this->user2Service->deleteUser2($id));
    }
}