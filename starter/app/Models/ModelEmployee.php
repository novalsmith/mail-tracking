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
        "level" => $this->getEmployeeByLevel($roleCode,$roleLevel)
       ];
        return $response;
    }

    function getEmployeeByLevel($roleCode, $roleLevel){ 
        $params = [
            ($roleCode ?? ''),
            ($roleLevel ?? '')
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getInboxActionList(?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        
        return $data;
    }
}
