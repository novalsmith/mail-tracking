<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ModelOtentikasi extends Model
{
    protected $table = "users";
    protected $primayKey = "userID";
    protected $allowedFields = ['email', 'password'];

    function getEmail($email)
    {
        $builder = $this->table("users");
        $data = $builder->where("email", $email)->first();
        if (!$data) {
            throw new Exception("Data otentikasi tidak ditemukan");
        }
        return $data;
    }
}
