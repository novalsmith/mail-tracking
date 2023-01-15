<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelPegawai extends Model
{
    protected $table = "users";
    protected $primaryKey = "userID";
    protected $allowedFields = ['name', 'email','password'];

    protected $validationRules = [
        'name' => 'required',
        'email' => 'required|valid_email'
    ];
    protected $validationMessages = [
        'name' => [
            'required' => 'Silakan masukkan nama'
        ],
        'email' => [
            'required' => 'Silakan masukkan email',
            'valid_email' => 'Email yang dimasukkan tidak valid'
        ]
    ];
}
