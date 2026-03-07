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
    
    public function __construct()
    {
        $this->baseUri = config('services.users1.base_uri');
    }

    public function obtainUsers1()
    {
        return $this->performRequest('GET','/users1');
    }
     /**
     * Create one user using the User1 service
     * @return string
     */
    
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