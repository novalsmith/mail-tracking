<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelEmployee extends Model
{
    protected $table = "v_employee";
    protected $primaryKey = "employeeId";

    function getEmployeeByParent($id){
        $builder = $this->table("v_employee");
        $builder->where("parent", $id);
       $data =  $builder->get()->getResult();
        return $data;
    }
}
