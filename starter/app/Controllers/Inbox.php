<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelInbox;
use App\Models\ModelHistory;
use App\Models\ModelOutbox;


class Inbox extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$this->model = new ModelInbox();
	}
	 
	public function index()
	{ 
		$searchingParams = $this->request->getVar('searchingParams');
		$data = $this->model->getInboxByUnit($searchingParams);
		return $this->respond($data, 200);
	}
	public function show()
	{ 
		$searchingParams = $this->request->getVar('searchingParams');
		$data = $this->model->getInboxById($searchingParams);
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
		$modelHistory = new ModelHistory(); 
		$modelOutbox = new ModelOutbox();
		$data = $this->request->getPost('listData');
		$isSuccess = false;
		$listData = json_decode($data); 
		$error = "";
		if(!empty($listData->inboxData)){
			foreach(array_chunk($listData->inboxData,count($listData->inboxData),true) as $rows) {
				$trackingData = $this->model->saveInboxData($rows);
				if($trackingData){
					$response = [
						"type" => 'inboxData',
						'status' => 200,
						'error' => null,
						'messages' => "Data Berhasil tersimpan"
					]; 
				}
			} 
		}else{
			$error = "Inbox gagal menyimpan data";
		}

		if(!empty($error)){
			return $this->failNotFound($error);
		}else{
			return $this->respond($data, 200);
		}
	
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
