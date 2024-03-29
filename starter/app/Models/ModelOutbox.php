<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelOutbox extends Model
{
    protected $table = "v_outbox";
    protected $primaryKey = "trackingid";
    protected $allowedFields = [
        'trackingid','agendaNumber','receiptDate','number',
    'realDate','type','note','from','to','description'];

    function getOutboxByUnit($searchingParams)
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
            ($searchingParams["assignedFrom"] ?? '')
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getOutbox(?,?,?,?,?,?,?,?,?,?,?,?,?)";
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
    public function saveOutboxData($data)
    {
        $isSuccess = false;
        $db = \Config\Database::connect();
        $builderTable = $db->table('outbox'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
        return  $isSuccess;
    }
 
}
