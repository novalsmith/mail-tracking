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
        // $builder = $this->db->query("getTracking()");
        // if(!empty($searchingParams)){ 
            // $number = $searchingParams["number"];
            // $agendaNumber = $searchingParams["agendaNumber"];
            // $from = $searchingParams["from"];
            // $to = $searchingParams["to"];
            // $ket = $searchingParams["ket"];
            // $note = $searchingParams["note"]; 
            // $dateActionTerimaStart = $searchingParams["dateActionTerimaStart"];
            // $dateActionTerimaEnd = $searchingParams["dateActionTerimaEnd"];
            // $dateActionSuratStart = $searchingParams["dateActionSuratStart"];
            // $dateActionSuratEnd = $searchingParams["dateActionSuratEnd"];
           
        //     if(!empty($agendaNumber)){
        //         $builder->like("agendaNumber",  $agendaNumber);
        //     }

        //     if(!empty($number)){
        //             $builder->like("number",  $number);
        //     }

        //     if(!empty($searchingParams["type"])){
        //         $builder->like("type",  $searchingParams["type"]);
        //     }

        //     if(!empty($from)){
        //         $builder->like("from",  $from);
        //     }

        //     if(!empty($to)){
        //         $builder->like("to",  $to);
        //     }

        //     if(!empty($ket)){
        //         $builder->like("ket",  $ket);
        //     }

        //     if(!empty($note)){
        //         $builder->like("note",  $note);
        //     }

        //     if(!empty($searchingParams["isUnknown"])){
        //         $builder->where("isUnknown",  $searchingParams["isUnknown"]);
        //     }

        //     if(!empty($dateActionTerimaStart) && !empty($dateActionTerimaEnd)){
        //        $builder->where("receiptDate BETWEEN '$dateActionTerimaStart' AND '$dateActionTerimaEnd'");
        //     //    $builder->where('receiptDate >=', $dateActionTerimaStart);
        //     //    $builder->where('receiptDate <=', $dateActionTerimaEnd);
        //     }else{
        //         if(!empty($dateActionTerimaStart)){
        //             $builder->where('receiptDate', $dateActionTerimaStart);
        //          }
        //     }

        //     if(!empty($dateActionSuratStart) && !empty($dateActionSuratEnd)){
        //         $builder->where("realDate BETWEEN '$dateActionSuratStart' AND '$dateActionSuratEnd'");
        //         // $builder->where('realDate >=', $dateActionSuratStart);
        //         // $builder->where('realDate <=', $dateActionSuratEnd);
        //      }else{
        //          if(!empty($dateActionSuratStart)){
        //              $builder->where("realDate",$dateActionSuratStart); 
        //           }
        //      }
        // }
        // $builder->where("isUnknown",'N');
        $db = \Config\Database::connect();
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
        $procedure = "CALL getTracking(?,?,?,?,?,?,?,?,?,?,?,?)";
        $builder = $this->db->query($procedure, $params); 
       $data =  $builder->getResult(); 
        return $data;
    }

    function validationUploadTracking()
    {
        // Calling from Stored Procedure
        $procedure = "CALL validationUploadTracking()";
        $builder = $this->db->query($procedure); 
        $data =  $builder->getResult(); 
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
        $builder = $db->table('v_tracking_temp');  
       $data =  $builder->get()->getResult(); 
        return $data;
    }

    function deleteData(){
        $db = \Config\Database::connect(); 
        $sql = "Delete from tracking_temp";  
        $db->query($sql);
            return   $sql;

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

    function saveDataTemp($data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $builderTable = $db->table('tracking_temp'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
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
        $builder = $this->table("v_tracking");
        $builder->where("number", $number);
        $builder->where("indexNumber", $indexNumber);
        $builder->where("fileName", $fileName);
       $data =  $builder->get()->getResult(); 
        return $data;
    }
}
