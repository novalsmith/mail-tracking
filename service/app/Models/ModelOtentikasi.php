<?php

namespace App\Models;

use CodeIgniter\Model;
use Exception;

class ModelOtentikasi extends Model
{
    protected $table = "v_access";
    protected $primayKey = "employeeId";
    protected $allowedFields = ['employeeId', 'password'];

    function getEmployee($employeeId,$password)
    {
        $builder = $this->table("v_access");
        $data = $builder->where("employeeId", $employeeId)->first();
        if (!$data) {
            throw new Exception("Wrong NIP");
        }
        if ($data['password'] != md5($password)) {
            throw new Exception("Wrong Password");
		}
        return $data;
    }
}
