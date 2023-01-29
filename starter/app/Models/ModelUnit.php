<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUnit extends Model
{
    protected $table = "v_position";
    protected $primaryKey = "clasificationId";

    function getUnitByPrefixNameFrom($value)
    {
        $value = strtolower(str_replace(' ', '', $value)); // remove whitespace and make it lower string
        $builder = $this->table("v_position");
        $data = $builder->where("oraganizationUnitPrefix", $value)->first();
        return $data;
    }

    function getUnitByPrefixNameTo($value)
    {
        $value = strtolower(str_replace(' ', '', $value)); // remove whitespace and make it lower string
        $builder = $this->table("v_position");
            $keywords = explode(';', $value);
            $test = array();
            foreach ($keywords as $val)
            {
                $test[] = $val;
            }
            
            $builder->whereIn("positionsPrefix",  $test);
            $data =  $builder->get()->getResult();
        
        return $data;
    }
}
