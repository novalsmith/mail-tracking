<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelUnknown;
use App\Models\ModelTracking;

class Unknown extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$this->model = new ModelUnknown();
	}
	 
	public function index()
	{
		$data = $this->model->getUnknown();
	    return $this->respond($data, 200);
	} 
	public function parent()
	{
		$unit = $this->request->getVar('roleCode');
		$level = $this->request->getVar('isAdmin');
		$number = $this->request->getVar('number');
		$unitTo = $this->request->getVar('unitTo');
		$data = [];
		$resultSingle = $this->model->getparentByNumberUnit($number,$unitTo); 
		if(!$resultSingle){
			$resultAll = $this->model->getparent($unit,$level); 
			$data["isEdit"] = false;
			$data["data"] = $resultAll;
		}else{
			$data["isEdit"] = true;
			$data["data"] = $resultSingle;
		}
	    return $this->respond($data , 200);
	} 
	
	public function updateInbox($listData)
	{
		$modelTracking = new ModelTracking(); 
		$isSuccess = false;
		if (!empty($listData)) {
			$resultExcelData = [];
			$message = "";
			$status = "";
			
			$trackingData = $this->model->getUnknownById($listData[0]);
			if(!$trackingData){
				$res = [
					"message" => "Unit sudah digunakan ditemukan"
				];
				return $this->respond(	$res , 404);
			}
			$params = [
				"trackingid" => $listData[0]->trackingid,
				"unitTo" => $listData[0]->to,
				"updatedDate" => $listData[0]->updatedDate,
				"updatedBy" => $listData[0]->updatedBy
			];		
			 
			$trackingData = $this->model->updateData($listData[0]->trackingid,$params);
			if($trackingData){
				$response = [
					"status" => 'success',
					"message" => ""
				];
				$resultExcelData[] = $response;
			}
			return $this->respond($trackingData, 200);
		} 
		return $this->failNotFound("Data gagal tersimpan, periksa dan coba lagi");

	}
	public function update($id = null)
	{
		 // todo
	}

 

	public function create()
	{
		$data = $this->request->getPost('listData');
		$isSuccess = false;
		if (!empty($data)) {
			$rows = json_decode($data); 
			// foreach(array_chunk($countData,count($countData),true) as $rows) {
				$trackingData = $this->model->saveData($rows);
				if($trackingData){ 
					$this->updateInbox($rows);
					$response = [
						'status' => 200,
						'error' => null,
						'messages' => "Data Berhasil tersimpan"
					];
					return $this->respond($response);
				}
			// }
		} 
		return $this->failNotFound("Data gagal tersimpan, periksa dan coba lagi");
	}

	public function delete(){
	$data = $this->request->getPost('listData');
		$isSuccess = false;
		if (!empty($data)) {
			$rows = json_decode($data);  
				$trackingData = $this->model->deleteData($rows[0]->trackingid);
				if($trackingData){
					$this->updateInbox($rows);
					$response = [
						'status' => 200,
						'error' => null,
						'messages' => "Data Berhasil dikembalikan"
					];
					return $this->respond($response);
				} 
		} 
		return $this->failNotFound("Data gagal dikembalikan, periksa dan coba lagi");
	}
}
