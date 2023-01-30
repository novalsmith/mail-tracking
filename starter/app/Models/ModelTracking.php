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

    function getTracking()
    {
        $builder = $this->table("v_tracking");
        $builder->where("to", $role);
       $data =  $builder->get()->getResult(); 
        return $data;
    }

    function getTrackingByRole($role)
    {
        $builder = $this->table("v_tracking");
        $builder->where("to", $role);
       $data =  $builder->get()->getResult(); 
        return $data;
    }

    function saveData($data){
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
