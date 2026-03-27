<?php

namespace App\Services;
use App\Traits\ConsumesExternalService;

class User2Service
{
    use ConsumesExternalService;
    /**
    * The base uri to consume the User2 Service
    * @var string
    */
    public $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.users2.base_uri');
        $this->secret = config('services.users2.secret');
    }

    //Get userjob
    public function obtainUserJob($jobid)
    {
        return $this->performRequest('GET', "/jobroles/{$jobid}");
    }

    //For get (all)
    public function obtainUsers2()
    {
        return $this->performRequest('GET','/employees');
    }
    
    //For Add
    public function createUser2($data)
    {
        return $this->performRequest('POST', '/employees', $data);
    }

    //For get by ID
    public function obtainUser2($empID)
    {
        return $this->performRequest('GET', "/employees/{$empID}");
    }

    //For Update
    public function editUser2($data, $empID)
    {
        return $this->performRequest('PUT', "/employees/{$empID}", $data);
    }

    //For Delete
    public function  deleteUser2($empID)
    {
        return $this->performRequest('DELETE', "/employees/{$empID}");
    }
}