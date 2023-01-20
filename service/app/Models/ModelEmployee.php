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

        if (!$data) {
            throw new Exception("Employee data not found");
        } 
        return $data;
    }
    // protected $allowedFields = ['name', 'email','password'];

    // protected $validationRules = [
    //     'name' => 'required',
    //     'email' => 'required|valid_email'
    // ];
    // protected $validationMessages = [
    //     'name' => [
    //         'required' => 'Silakan masukkan nama'
    //     ],
    //     'email' => [
    //         'required' => 'Silakan masukkan email',
    //         'valid_email' => 'Email yang dimasukkan tidak valid'
    //     ]
    // ];
}
