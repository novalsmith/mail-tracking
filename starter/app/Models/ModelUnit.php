<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUnit extends Model
{
    protected $table = "v_position";
    protected $primaryKey = "clasificationId";

    // getUnitByPrefixNameFrom comment will be use for ither logic
    // function getUnitByPrefixNameFrom($value)
    // {
    //     $value = strtolower(str_replace(' ', '', $value)); // remove whitespace and make it lower string
    //     $builder = $this->table("v_position");
    //     $data = $builder->where("oraganizationUnitPrefix", $value)->first();
    //     return $data;
    // }

    function getUnitByPrefixNameTo($value)
    {
        $value = strtolower(str_replace(' ', '', $value)); // remove whitespace and make it lower string
        $builder = $this->table("v_position");
            $keywords = explode(';', $value); // split string to array with explode based on semmi colon delimiters.
            $test = array();
            foreach ($keywords as $val) 
            {
                $test[] = $val; // loop the emploded array and stored into new array object
            }
            
            $builder->whereIn("positionsPrefix",  $test);
            $builder->orWhereIn("codePrefix",  $test);
            $data =  $builder->get()->getResult();
        
        return $data;
    }
}
