<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelOutbox;

class Outbox extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$this->model = new ModelOutbox();
	}
	 
	public function index()
	{
		// $code = $this->request->getVar('code');
 		// $data = $this->model->getOutboxByUnit($code);
	    // return $this->respond($data, 200);
		$searchingParams = $this->request->getVar('searchingParams');
		$data = $this->model->getOutboxByUnit($searchingParams);
		return $this->respond($data, 200);
	}
	public function log()
	{
		$dataParam = $this->request->getPost('agendaNumber');
		$data = $this->model->getLog($dataParam);
	    return $this->respond($data, 200);
	} 
	public function create()
	{
		$data = $this->request->getPost('listData');
		$isSuccess = false;
		if (!empty($data)) {
			$countData = json_decode($data); 
			foreach(array_chunk($countData,count($countData),true) as $rows) {
				$trackingData = $this->model->saveData($rows);
				if($trackingData){
					$response = [
						'status' => 200,
						'error' => null,
						'messages' => "Data Berhasil tersimpan"
					];
					return $this->respond($response);
				}
			}
		} 
		return $this->failNotFound("Data gagal tersimpan, periksa dan coba lagi");
	}
	public function update($id = null)
	{
		 // todo
	}

	public function delete($id = null)
	{
		 // toto
	}
}
