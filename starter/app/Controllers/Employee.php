<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelEmployee;

class Employee extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$this->model = new ModelEmployee();
	}
	public function index()
	{
		$searchingParams = $this->request->getVar('searchingParams');
		$data = $this->model->getEmployee($searchingParams);
		return $this->respond($data, 200);
	}
	public function employeeByParent()
	{
		$roleCode = $this->request->getVar('roleCode');
		$roleLevel = $this->request->getVar('roleLevel');
		$modelData = new ModelEmployee();
		$data = $modelData->getEmployeeByParent($roleCode,$roleLevel);
			return $this->respond($data, 200);
	}
	
	public function historyJabatan()
	{
		$employeeId = $this->request->getVar('employeeId');
		$modelData = new ModelEmployee();
		$data = $modelData->getHistoryJabatan($employeeId);
			return $this->respond($data, 200);
	}

	public function savePosition()
	{
		// $dataParam = $this->request->getPost();
		$dataParam = $this->request->getPost('listData');
		$isSuccess = false;
		$listData = json_decode($dataParam); 
		// $error = "";
		// if(!empty($listData->inboxData)){

		$modelData = new ModelEmployee();
		$data = $modelData->savePosition($listData->employeeId,$listData->jabatan,$listData->startDate, $listData->endDate, $listData->status
		, $listData->isEdit, $listData->roleCodeValueEdit);
			return $this->respond($data, 200);
	}

	public function getPostition()
	{
		// $employeeId = $this->request->getVar('employeeId');
		$modelData = new ModelEmployee();
		$data = $modelData->getPostition();
			return $this->respond($data, 200);
	}

	public function create()
	{
		$data = $this->request->getPost();
		if (!$this->model->save($data)) {
			return $this->fail($this->model->errors());
		}
		$response = [
			'status' => 201,
			'error' => null,
			'messages' => [
				'success' => 'Berhasil memasukkan data pegawai'
			]
		];
		return $this->respond($response);
	}
	public function update($id = null)
	{
		$data = $this->request->getRawInput();
		$data['id'] = $id;

		$isExists = $this->model->where('id', $id)->findAll();
		if (!$isExists) {
			return $this->failNotFound("Data tidak ditemukan untuk id $id");
		}

		if (!$this->model->save($data)) { //kalau ada error saat menyimpan
			return $this->fail($this->model->errors());
		}

		$response = [
			'status' => 200,
			'error' => null,
			'messages' => [
				'success' => "Data pegawai dengan id $id berhasil diupdate"
			]
		];
		return $this->respond($response);
	}

	public function delete($id = null)
	{
		$data = $this->model->where('id', $id)->findAll();
		if ($data) {
			$this->model->delete($id);
			$response = [
				'status' => 200,
				'error' => null,
				'messages' => [
					'success' => 'Data berhasil dihapus'
				]
			];
			return $this->respondDeleted($response);
		} else {
			return $this->failNotFound('Data tidak ditemukan');
		}
	}
}
