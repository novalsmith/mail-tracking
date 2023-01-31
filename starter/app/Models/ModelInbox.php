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

    function getInboxByRole($param)
    {
        $builder = $this->table("v_inbox");
        $builder->whereIn("to", $param);
       return  $builder->get()->getResult();
       
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
        $builderTable = $db->table('tracking'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
        return  $isSuccess;
    }
 
}
