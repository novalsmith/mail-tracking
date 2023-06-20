<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelEmployee extends Model
{
    protected $table = "v_employee";
    protected $primaryKey = "employeeId";

    function getEmployee($searchingParams)
    {
        $db = \Config\Database::connect();
        $db->reconnect();
        $params = [
            ($searchingParams["employeeIdValue"] ?? ''),
            ($searchingParams["nameValue"] ?? ''),
            ($searchingParams["jabatanValue"] ?? ''),
            ($searchingParams["kodeValue"] ?? ''),
            ($searchingParams["levelValue"] ?? '') 
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getEmployee(?,?,?,?,?)";
        $builder = $db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();

        return $data;
    }

    function getHistoryJabatan($employeeId)
    {
        $db = \Config\Database::connect();
        $db->reconnect();
        $params = [
            ($employeeId ?? '')
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getHistoryJabatan(?)";
        $builder = $db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();

        return $data;
    }

    function savePosition($employeeId, $jabatan, $startDate,$endDate,$status,$isEdit,$roleCodeValueEdit){
        $params = [
            ($employeeId ?? ''),
            ($jabatan ?? ''),
            ($startDate ?? null),
            ($endDate ?? null),
            ($status ?? null),
            ($isEdit ?? null),
            ($roleCodeValueEdit ?? null)
        ];
        // Calling from Stored Procedure
        $procedure = "CALL upsertHistoryJabatan(?,?,?,?,?,?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        
        return $data;
    }

    function getPostition()
    {
        $db = \Config\Database::connect();
        $db->reconnect(); 
        // Calling from Stored Procedure
        $procedure = "CALL getPostition()";
        $builder = $db->query($procedure); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();

        return $data;
    }

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
