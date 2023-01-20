<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelAccess extends Model
{
    protected $table = "v_access";
    protected $primaryKey = "accessid";
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
