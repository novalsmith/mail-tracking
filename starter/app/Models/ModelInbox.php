<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelInbox extends Model
{
    protected $table = "v_inbox";
    protected $primaryKey = "trackingid";
    protected $allowedFields = [
        'trackingid','agendaNumber','receiptDate','number',
    'realDate','type','note','from','to','description'];

    function getInboxByUnit($searchingParams)
    { 
        $db = \Config\Database::connect();
        $params = [
            ($searchingParams['employeeId'] ?? ''),
            ($searchingParams["agendaNumber"] ?? ''),
            ($searchingParams["number"] ?? ''),
            ($searchingParams["type"] ?? ''),
            ($searchingParams["from"] ?? ''),
            ($searchingParams["to"] ?? ''),
            ($searchingParams["ket"] ?? ''),
            ($searchingParams["note"] ?? ''), 
            ($searchingParams["dateActionTerimaStart"] ?? ''),
            ($searchingParams["dateActionTerimaEnd"] ?? ''),
            ($searchingParams["dateActionSuratStart"] ?? ''),
            ($searchingParams["dateActionSuratEnd"] ?? ''),
            ($searchingParams["unitTo"] ?? ''),
            ($searchingParams["isUnknown"] ?? '')
            
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getInbox(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        return $data;
       
    }
    function getInboxById($searchingParams)
    { 
        $db = \Config\Database::connect();
        $params = [
            ($searchingParams['trackingId'] ?? '')
            
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getInboxById(?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        return $data;
       
    }
    
    public function getLog($agendaNumber)
    {
        $dbs = \Config\Database::connect();
        $builderTable = $dbs->table('v_tracking')->where("agendaNumber", $agendaNumber);
       $data =  $builderTable->get()->getResult(); 
       $list = [
        "logData" => $data,
        "maxData" => $this->getMaxSequenceByAgendaNumber($agendaNumber)
       ];
        return $list;
    }
    public function getMaxSequenceByAgendaNumber($agendaNumber)
    {
        $dbs = \Config\Database::connect();
        $builderTable = $dbs->table('v_tracking')->where("agendaNumber", $agendaNumber)->selectMax('sequence','sequenceTotal');
       $data =  $builderTable->get()->getRow()->sequenceTotal; 
        return $data;
    }
    public function saveData($data)
    {
        $isSuccess = false;
        $db = \Config\Database::connect();
        $builderTable = $db->table('formtransaction'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
        return  $isSuccess;
    }

    public function isExistsInboxData(){
        $db = \Config\Database::connect();
        // Calling from Stored Procedure
        $procedure = "CALL getExistsInbox()";
        $builder = $this->db->query($procedure); 
        $data =  $builder->getResult(); 
        return $data;
    }

    function saveInboxData($data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $db->reconnect();
        $builderTable = $db->table('inboxTemporary'); 
        $response = $builderTable->insertBatch($data);

        if($response){
            // Calling from Stored Procedure
            $procedure = "CALL inboxUpsert()";
            $builder = $this->db->query($procedure); 
             $builder->getResult(); 
            
            $isSuccess = true;
        }
        $db->close();
        $db->initialize();
        return  $isSuccess;
    }

    function saveUploadInboxData($data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $db->reconnect();
        $builderTable = $db->table('inbox'); 
        $response = $builderTable->insertBatch($data);

        if($response){ 
            $isSuccess = true;
        }
        $db->close();
        $db->initialize();
        return  $isSuccess;
    }
 
}
