<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelOtentikasi;
use App\Models\ModelMasterDataLookup;

class Otentikasi extends BaseController
{
	use ResponseTrait;
	public function index()
	{
		helper('jwt');
		$nip = "";
		$password = "";

		$isWebAdmin = $this->request->getVar('isWebAdmin');
		$validation = \Config\Services::validation();
			$aturan = [
				'nip' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Silakan masukkan nip'
					]
				],
				'password' => [
					'rules' => 'required',
					'errors' => [
						'required' => 'Silakan masukkan password',
	
					]
				]
			];
			$validation->setRules($aturan);
			if (!$validation->withRequest($this->request)->run()) {
				return $this->fail($validation->getErrors());
			}

			$nip = $this->request->getVar('nip');
			$password = $this->request->getVar('password');
		 

		$model = new ModelOtentikasi();
		$modelMasterData = new ModelMasterDataLookup();
		$data = $model->getEmployee($nip,$password); 
		// if ($data['password'] != md5($password)) {
		// 	return $this->fail("Password tidak sesuai");
		// }

		$userData['employeeId'] =  $data['employeeId'];
		$userData['name'] =  $data['name'];
		$userData['roleLevel'] =  $data['roleLevel'];
		$userData['roleLevelName'] =  $data['roleLevelName'];
		$userData['roleCode'] =  $data['roleCode'];
		$userData['status'] =  $data['status'];
		$userData['parent'] =  $data['parent'];
		  
		$response = [
			'message' => 'Otentikasi berhasil dilakukan',
			'user' => $userData,  
			'access_token' => createJWT($nip,$password)
		];
		return $this->respond($response);
	}
}
