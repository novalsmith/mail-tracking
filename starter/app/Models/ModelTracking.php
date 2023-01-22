<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelTracking extends Model
{
    protected $table = "v_tracking";
    protected $primaryKey = "trackingid";
    protected $allowedFields = ['trackingid','to'];

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

    function getTrackingByRole($role)
    {
        $builder = $this->table("v_tracking");
        $builder->where("to", $role);
       $data =  $builder->get()->getResult(); 
        return $data;
    }
}
