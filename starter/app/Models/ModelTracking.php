<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelTracking extends Model
{
    protected $table = "v_tracking";
    protected $primaryKey = "trackingid";
    protected $allowedFields = [
        'trackingid','agendaNumber','receiptDate','number',
    'realDate','type','note','from','to','description'];

    function getTracking($searchingParams)
    {
        $db = \Config\Database::connect();
        $db->reconnect();
        $params = [
            ($searchingParams["agendaNumber"] ?? ''),
            ($searchingParams["number"] ?? ''),
            ($searchingParams["type"] ?? ''),
            ($searchingParams["from"] ?? ''),
            ($searchingParams["to"] ?? ''),
            ($searchingParams["ket"] ?? ''),
            ($searchingParams["note"] ?? ''),
            ($searchingParams["isUnknown"] ?? ''),
            ($searchingParams["dateActionTerimaStart"] ?? ''),
            ($searchingParams["dateActionTerimaEnd"] ?? ''),
            ($searchingParams["dateActionSuratStart"] ?? ''),
            ($searchingParams["dateActionSuratEnd"] ?? '')
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getNadine(?,?,?,?,?,?,?,?,?,?,?,?)";
        $builder = $db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();

        return $data;
    }

    function validationUploadTracking()
    {
        $db = \Config\Database::connect();
        $db->reconnect();
        // Calling from Stored Procedure
        $procedure = "CALL validationUploadTracking()";
        $builder = $db->query($procedure); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();
        return $data;
    }

    function getTrackingByRole($role)
    {
        $builder = $this->table("v_tracking");
        $builder->where("to", $role);
        $builder->orWhere("isUnknown", 'N');
        $data =  $builder->get()->getResult(); 
        return $data;
    }

    function getTrackingTemp()
    {
        $db = \Config\Database::connect();
        $db->reconnect();
        $builder = $db->table('v_tracking_temp');  
       $data =  $builder->get()->getResult(); 
       $db->close();
       $db->initialize();
        return $data;
    }

    function deleteData(){
        $db = \Config\Database::connect();
        $db->reconnect(); 
        $sql = "Delete from tracking_temp";  
        $db->query($sql);
        $db->close();
        $db->initialize();
        return   $sql;
    }
    
    function saveNadineData($data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $db->reconnect();
        $builderTable = $db->table('nadine'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
        $db->close();
        $db->initialize();
        return  $isSuccess;
    }

    function saveDataTemp($data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $db->reconnect();
        $builderTable = $db->table('tracking_temp'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
        $db->close();
        $db->initialize();
        return  $isSuccess;
    }

    function updateData(){
        $db = \Config\Database::connect();
            $sql = "UPDATE tracking_temp
            tracking_temp  INNER JOIN 
             v_tracking_temp_duplicates  ON tracking_temp.number = v_tracking_temp_duplicates.number
            SET tracking_temp.`status` = 'unknown'";  
            $db->query($sql);
                return   $sql;
    }

    function validateDumplicate($number,$fileName, $indexNumber){
        $db = \Config\Database::connect();
        $db->reconnect();
        $builder = $db->table("v_tracking");
        $builder->where("number", $number);
        $builder->where("indexNumber", $indexNumber);
        $builder->where("fileName", $fileName);
       $data =  $builder->get()->getResult(); 
       $db->close();
       $db->initialize();
        return $data;
    }
}
