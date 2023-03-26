<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelFileUpload extends Model
{
    protected $table = "v_unknown";
    protected $primaryKey = "trackingid";
    protected $allowedFields = [
        'trackingid','agendaNumber','receiptDate','number',
    'realDate','type','note','from','to','description'];

     
    function saveFileUploadData($data){
        $isSuccess = false;
        $db = \Config\Database::connect();
        $db->reconnect();
        $builderTable = $db->table('fileUpload'); 
        $response = $builderTable->insertBatch($data);
        if($response){
            $isSuccess = true;
        }
        $db->close();
        $db->initialize();
        return  $isSuccess;
    } 
}
