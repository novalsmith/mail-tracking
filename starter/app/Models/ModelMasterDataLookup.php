<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ModelMasterDataLookup extends Model
{
    protected $table = "v_masterdatalookup";
    protected $primayKey = "idmasterdatalookup";
    protected $allowedFields = ['idmasterdatalookup', 'type'];

    function getMasterDataLookup($type)
    {
        $builder = $this->table("v_masterdatalookup");
         $builder->where("type", $type);
        $data =  $builder->get()->getResult();
        
        if (!$data) {
            throw new Exception("No Data");
        }
        return $data;
    }
}
