<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelHistory extends Model
{
    protected $table = "v_unknown";
    protected $primaryKey = "trackingid";
    protected $allowedFields = [
        'trackingid','agendaNumber','receiptDate','number',
    'realDate','type','note','from','to','description'];

     
    
    function getHistoryHeader($trackingId){
        $db = \Config\Database::connect();
        $params = [
            ($trackingId ?? ''),
            ('Y')
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getHistoryHeader(?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        $responseData = [
            "header" =>  $data,
            "headerDetail" => $this->getHistoryDetailHeader($trackingId)
        ];
        return $responseData;
    } 

    function getHistoryDetailHeader($trackingId){
        $db = \Config\Database::connect();
        $params = [
            ($trackingId ?? ''),
            ('')
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getHistoryHeader(?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        return $data;
    } 

    function saveHistoryData($data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $db->reconnect();
        $builderTable = $db->table('history'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
        $db->close();
        $db->initialize();
        return  $isSuccess;
    } 
}
