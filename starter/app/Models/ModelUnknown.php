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

    function getUnknown($searchingParams)
    { 
        $db = \Config\Database::connect();
        $db->reconnect();
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
        $procedure = "CALL getUnknown(?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();
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

    function getUnknownById($nomorSurat,$unitAssignedFrom)
    {
    //     $dbs = \Config\Database::connect();  
    //     $builder = $dbs->table("v_tracking");
    //     // $builder->where('trackingid',$param->trackingid);
    //    $data =  $builder->where('unitTo <>',$param->to);
    //     return $data->get()->getResult(); 
        $db = \Config\Database::connect();
        $db->reconnect();
        $paramValue["unitAssignedFrom"] = $unitAssignedFrom;
        $paramValue["nomorSurat"] = $nomorSurat;
        $params = [
            $paramValue["unitAssignedFrom"],
            $paramValue["nomorSurat"]
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getUnknownById(?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();
        return $data;

    }

    function getparent($unit,$isAdmin,$nomorSurat,$unitAssignedFrom,$unknownId){
    //     $dbs = \Config\Database::connect();  
    //     $builderTable = $dbs->table('v_unknown_unit');
    //     if($isAdmin != 0){
    //         $builderTable->where("parent", $unit);  
    //     }
    //    $data =  $builderTable->get()->getResult(); 
    //     return $data;

        $db = \Config\Database::connect();
        $db->reconnect();
        $paramValue["isAdmin"] = $isAdmin;
        $paramValue["unitParent"] = $unit;
        $paramValue["unitAssignedFrom"] = $unitAssignedFrom;
        $paramValue["nomorSurat"] = $nomorSurat;
        $paramValue["unknownId"] = $unknownId;
        $params = [
            $paramValue["isAdmin"],
            $paramValue["unitParent"],
            $paramValue["unitAssignedFrom"],
            $paramValue["nomorSurat"],
            $paramValue["unknownId"]
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getUnknownByParent(?,?,?,?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();
        return $data;
        
    }

    function getparentByNumberUnit($number, $unitTo){
    //     $dbs = \Config\Database::connect();  
    //     $builderTable = $dbs->table('v_unknown_unit');
    //     $builderTable->where("unitTo", $unitTo); 
    //     $builderTable->where("number", $number); 

    //    $data =  $builderTable->get()->getResult(); 
    //     return $data;
    $searchingParams["number"] = $number;
    $searchingParams["unitAssignedTo"] = $unitTo;
        $db = \Config\Database::connect();
        $db->reconnect();
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
            ($searchingParams["unitAssignedTo"] ?? '')
            
        ];
        // Calling from Stored Procedure
        $procedure = "CALL getUnknown(?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data =  $builder->getResult(); 
        $db->close();
        $db->initialize();
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

    public function createDeleteUnknownInbox($unknownIdVal,$trackingId,$assignedFromEmployeeId,$assignedToEmployeeId,$isCreate)
    {
        // $isSuccess = false;
        // $db = \Config\Database::connect();
        // $builderTable = $db->table('inbox'); 
        // $response = $builderTable->insertBatch($data);
        // if($response){
        //     $isSuccess = true;
        // }
        // return  $isSuccess;

        $db = \Config\Database::connect();
        $db->reconnect();
        $paramValue["unknownIdVal"] = $unknownIdVal;
        $paramValue["trackingIdVal"] = $trackingId;
        $paramValue["assignedFromEmployeeId"] = $assignedFromEmployeeId;
        $paramValue["assignedToEmployeeId"] = $assignedToEmployeeId;
        $paramValue["isCreate"] = $isCreate;
        $params = [
            $paramValue["unknownIdVal"],
            $paramValue["trackingIdVal"],
            $paramValue["assignedFromEmployeeId"], 
            $paramValue["assignedToEmployeeId"],
            $paramValue["isCreate"]
        ];
        // Calling from Stored Procedure
        $procedure = "CALL createDeleteUnknownInbox(?,?,?,?,?)";
        $builder = $this->db->query($procedure, $params); 
        $data = $builder->getResult(); 
        $db->close();
        $db->initialize();
        return $data;
    }

    function saveUnknownData($data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $builderTable = $db->table('unknown'); 
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
