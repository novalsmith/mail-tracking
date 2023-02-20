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
	public function unitParent()
	{
		$unit = $this->request->getVar('roleCode');
		$level = $this->request->getVar('isAdmin');
		$data = $this->model->getUnitParent($unit,$level); 
	    return $this->respond($data , 200);
	} 
	
	public function create()
	{
		$modelTracking = new ModelTracking(); 
		$data = $this->request->getPost('listData');
		$trackingId = $this->request->getPost('trackingid');
		$isSuccess = false;
		if (!empty($data)) {
			$listData = json_decode($data); 
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
				"unitTo" => $listData[0]->unitTo,
				"updatedDate" => $listData[0]->updatedDate,
				"updatedBy" => $listData[0]->updatedBy
			];		
			 
			$trackingData = $this->model->updateData($trackingId,$params);
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

	public function delete($id = null)
	{
		 // toto
	}
}
