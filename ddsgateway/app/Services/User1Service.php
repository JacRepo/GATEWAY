<?php

namespace App\Services;
use App\Traits\ConsumesExternalService;

class User1Service
{
    use ConsumesExternalService;
    /**
    * The base uri to consume the User1 Service
    * @var string
    */
    public $baseUri;
    
    /**
    * The secret to consume the User1 Service
    * @var string
    */
    public $secret;

    public function __construct()
    {
        $this->baseUri = config('services.users1.base_uri');
        $this->secret = config('services.users1.secret');
    }

    //get userjob
    public function obtainUserJob($jobid)
    {
        return $this->performRequest('GET', "/userjob/{$jobid}");
    }

    //Get Users
    public function obtainUsers1()
    {
        return $this->performRequest('GET','/users1');
    }
    
    //For Add
    public function createUser1($data)
    {
        return $this->performRequest('POST', '/users1', $data);
    }

    //For get
    public function obtainUser1($id)
    {
        return $this->performRequest('GET', "/users1/{$id}");
    }

    //For Update
    public function editUser1($data, $id)
    {
        return $this->performRequest('PUT', "/users1/{$id}", $data);
    }

    //For Delete
    public function  deleteUser1($id)
    {
        return $this->performRequest('DELETE', "/users1/{$id}");
    }
}