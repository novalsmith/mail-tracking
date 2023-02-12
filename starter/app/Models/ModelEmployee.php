<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelEmployee extends Model
{
    protected $table = "v_employee";
    protected $primaryKey = "employeeId";

    function getEmployeeByParent($roleCode,$roleLevel){
        $builder = $this->table("v_employee");
        $builder->where("parent", $roleCode);
       $data =  $builder->get()->getResult();
       $response = [
        "parent" =>  $data,
        "level" => $this->getEmployeeByLevel($roleLevel)
       ];
        return $response;
    }

    function getEmployeeByLevel($roleLevel){
        $builder = $this->table("v_employee");
        $builder->where("level", $roleLevel);
       $data =  $builder->get()->getResult();
        return $data;
    }
}
