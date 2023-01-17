<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelOtentikasi;

class Otentikasi extends BaseController
{
	use ResponseTrait;
	public function index()
	{
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
		$data = $model->getEmployee($nip);
		if ($data['password'] != md5($password)) {
			return $this->fail("Password tidak sesuai");
		}

		helper('jwt');
		$response = [
			'message' => 'Otentikasi berhasil dilakukan',
			'user' => $data,
			'access_token' => createJWT($nip)
		];
		return $this->respond($response);
	}
}
