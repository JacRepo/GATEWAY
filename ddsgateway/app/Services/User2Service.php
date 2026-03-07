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
    }

    //For get (all)
    public function obtainUsers2()
    {
        return $this->performRequest('GET','/users2');
    }
     /**
     * Create one user using the User2 service
     * @return string
     */
    
    //For Add
    public function createUser2($data)
    {
        return $this->performRequest('POST', '/users2', $data);
    }

    //For get by ID
    public function obtainUser2($id)
    {
        return $this->performRequest('GET', "/users2/{$id}");
    }

    //For Update
    public function editUser2($data, $id)
    {
        return $this->performRequest('PUT', "/users2/{$id}", $data);
    }

    //For Delete
    public function  deleteUser2($id)
    {
        return $this->performRequest('DELETE', "/users2/{$id}");
    }
}