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
        $builder = $this->table("v_unknown");
       $data =  $builder->get()->getResult(); 
        return $data;
    }

    function getUnknowns($searchingParams)
    {
        $builder = $this->table("v_inbox");
        if(!empty($searchingParams)){
            $type = $searchingParams["type"];
            $number = $searchingParams["number"];
            $agendaNumber = $searchingParams["agendaNumber"];
            $from = $searchingParams["from"];
            $to = $searchingParams["to"];
            $ket = $searchingParams["ket"];
            $note = $searchingParams["note"];
            $dateActionTerimaStart = $searchingParams["dateActionTerimaStart"];
            $dateActionTerimaEnd = $searchingParams["dateActionTerimaEnd"];
            $dateActionSuratStart = $searchingParams["dateActionSuratStart"];
            $dateActionSuratEnd = $searchingParams["dateActionSuratEnd"];
           
            if(!empty($agendaNumber)){
                $builder->like("agendaNumber",  $agendaNumber);
            }

            if(!empty($number)){
                    $builder->like("number",  $number);
            }

            if(!empty($type)){
                $builder->like("type",  $type);
            }

            if(!empty($from)){
                $builder->like("from",  $from);
            }

            if(!empty($to)){
                $builder->like("to",  $to);
            }

            if(!empty($ket)){
                $builder->like("ket",  $ket);
            }

            if(!empty($note)){
                $builder->like("note",  $note);
            }

            if(!empty($dateActionTerimaStart) && !empty($dateActionTerimaEnd)){
               $builder->where("receiptDate BETWEEN '$dateActionTerimaStart' AND '$dateActionTerimaEnd'");
            //    $builder->where('receiptDate >=', $dateActionTerimaStart);
            //    $builder->where('receiptDate <=', $dateActionTerimaEnd);
            }else{
                if(!empty($dateActionTerimaStart)){
                    $builder->where('receiptDate', $dateActionTerimaStart);
                 }
            }

            if(!empty($dateActionSuratStart) && !empty($dateActionSuratEnd)){
                $builder->where("realDate BETWEEN '$dateActionSuratStart' AND '$dateActionSuratEnd'");
                // $builder->where('realDate >=', $dateActionSuratStart);
                // $builder->where('realDate <=', $dateActionSuratEnd);
             }else{
                 if(!empty($dateActionSuratStart)){
                     $builder->where("realDate",$dateActionSuratStart); 
                  }
             }
        }
        $builder->where("isUnknown",'N');
       $data =  $builder->get()->getResult(); 
        return $data;
    }

    function getUnknownById($param)
    {
        $dbs = \Config\Database::connect();  
        $builder = $dbs->table("v_tracking");
        // $builder->where('trackingid',$param->trackingid);
       $data =  $builder->where('unitTo <>',$param->to);
        return $data->get()->getResult(); 
    }

    function getparent($unit,$isAdmin){
        $dbs = \Config\Database::connect();  
        $builderTable = $dbs->table('v_unknown_unit');
        if($isAdmin != 0){
            $builderTable->where("parent", $unit);  
        }
       $data =  $builderTable->get()->getResult(); 
        return $data;
    }

    function getparentByNumberUnit($number, $unitTo){
        $dbs = \Config\Database::connect();  
        $builderTable = $dbs->table('v_unknown_unit');
        $builderTable->where("unitTo", $unitTo); 
        $builderTable->where("number", $number); 

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

    public function saveData($data)
    {
        $isSuccess = false;
        $db = \Config\Database::connect();
        $builderTable = $db->table('unknown_transaction'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
        return  $isSuccess;
    }

    public function deleteData($id)
    {
        $db = \Config\Database::connect();
        $builderTable = $db->table('unknown_transaction'); 
        $response =  $builderTable->where('trackingid', $id)->delete();
        $isSuccess = false;
       
        if($response){
            $isSuccess = true;
        }
        return  $isSuccess;
    }
 
}
