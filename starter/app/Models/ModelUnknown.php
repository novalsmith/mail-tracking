<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUnknown extends Model
{
    protected $table = "v_unknown";
    protected $primaryKey = "trackingid";
    protected $allowedFields = [
        'trackingid','agendaNumber','receiptDate','number',
    'realDate','type','note','from','to','description'];

    function getUnknown()
    {
        $builder = $this->table("v_inbox");
       $data =  $builder->get()->getResult(); 
        return $data;
    }

    function getUnknownById($param)
    {
        $dbs = \Config\Database::connect();  
        $builder = $dbs->table("v_tracking");
        // $builder->where('trackingid',$param->trackingid);
       $data =  $builder->where('unitTo <>',$param->unitTo);
        return $data->get()->getResult(); 
    }

    function getUnitParent($unit){
        $dbs = \Config\Database::connect();  
        $builderTable = $dbs->table('v_unknown_unit');
        $builderTable->where("parent", $unit); 

       $data =  $builderTable->get()->getResult(); 
        return $data;
    }

    function updateData($id,$data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $builderTable = $db->table('tracking'); 
        $builderTable->where('trackingid', $id);
        $response = $builderTable->update($data);

        if($response){
            $isSuccess = true;
        }
        return  $isSuccess;
    }
 
}
