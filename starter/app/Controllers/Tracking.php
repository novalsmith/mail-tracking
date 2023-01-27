<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelTracking;

class Tracking extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$this->model = new ModelTracking();
	}
	public function index()
	{
		$data = $this->model->findAll();
		return $this->respond($data, 200);
	}
	public function show($id)
	{
		// $role = $this->request->getVar('role');
		$modelData = new ModelTracking();
		$data = $modelData->getTrackingByRole($id);
		if ($data) {
			return $this->respond($data, 200);
		} else {
			return $this->failNotFound("Data not found for id $id");
		}
	}

	public function importCsvToDb()
    {
       
            if($file = $this->request->getFile('fileUpload')) {
            if ($file->isValid() && ! $file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
                $i = 0;
                $numberOfFields = 2;
                $csvArr = array();
                
                while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                    $num = count($filedata);
                    if($i > 0 && $num == $numberOfFields){ 
                      print_r($filedata);
						// $csvArr[$i]['agendaNumber'] = $filedata[0];
                        // $csvArr[$i]['receiptDate'] = $filedata[1];
                        // $csvArr[$i]['number'] = $filedata[2];
                        // $csvArr[$i]['realDate'] = $filedata[3];
						// $csvArr[$i]['type'] = $filedata[4];
						// $csvArr[$i]['note'] = $filedata[5];
						// $csvArr[$i]['from'] = $filedata[6];
						// $csvArr[$i]['to'] = $filedata[7];
						// $csvArr[$i]['description'] = $filedata[8];
                    }
                    $i++;
                }
                fclose($file);
                $count = 0;
			 
				$listdata = [];
                // foreach($csvArr as $userdata){
					 

				// 	$listdata['list'] =  $userdata['agendaNumber'];
                // }
				

				// return $this->respond(json_encode($listdata['list'], JSON_INVALID_UTF8_SUBSTITUTE), 200);
				
                // session()->setFlashdata('message', $count.' rows successfully added.');
                // session()->setFlashdata('alert-class', 'alert-success');
            }
            else{
                // session()->setFlashdata('message', 'CSV file coud not be imported.');
                // session()->setFlashdata('alert-class', 'alert-danger');
				return $this->failNotFound($file);
            }
		}
			
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
