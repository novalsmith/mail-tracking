<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelTracking;
use App\Models\ModelUnit;
use App\Models\ModelInbox;
use App\Models\ModelUnknown;
use App\Models\ModelHistory;
use App\Models\ModelFileUpload; 

class Tracking extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$this->model = new ModelTracking();
	}
	public function index()
	{
		$searchingParams = $this->request->getVar('searchingParams');
		$data = $this->model->getTracking($searchingParams);
		return $this->respond($data, 200);
	}
	public function show($id)
	{
		// $role = $this->request->getVar('role');
		$modelData = new ModelTracking();
		$data = $modelData->getTrackingByRole($id);
	 
			return $this->respond($data, 200);
		 
	}
	
	public function removeUnusedFile(){
		$fileName = $this->request->getVar('fileName');
		if(file_exists(ROOTPATH.'public/nadineFiles/'.$fileName)){
			if(unlink(ROOTPATH.'public/nadineFiles/'.$fileName)){
				$sample = [
					"data" => 'success'
				];
				return $this->respond($sample,200);
			}else{
				return $this->respond("File $fileName Cannot deleted",404);
			}
		}
		else{
			return $this->respond("File $fileName Not Found",404);
		}
	}
	public function importCsvToDb()
    {
		$modelUnit = new ModelUnit();
		$file_excel = $this->request->getFile('TrackingFileUpload');
		$unitTo = $this->request->getPost('to');
		$ext = $file_excel->getClientExtension();
		if($ext == 'xls') {
			$render = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		} else if($ext == 'xlsx') {
			$render = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}else{
			$dataError = [
				"message" => "Sorry the file type .$ext is not supported, please use .xls or .xlxs"
			];
			return $this->respond($dataError,404);
			// return $this->respond("Please use .xls or .xlxs",500);
		}
		$spreadsheet = $render->load($file_excel);
		$fileName = $file_excel->getName();
		$file_excel->move('../public/nadineFiles', $fileName);
		$chunkSize = 50;
		$startRow = 0;
		$maxRows = 3000;
		$data = $spreadsheet->getActiveSheet()->toArray();
		$validation = $spreadsheet->getActiveSheet()->getCell('J1');
		if(empty($validation->getValue())){
			$dataError = [
				"message" => "Maaf, file excel $fileName tidak memiliki Kolom 'Nomor'. Silahkan periksa lagi template anda dan coba lagi."
			];
			return $this->respond($dataError,404);
		} 
		$resultExcelData = [];
		$resultExcelDataDuplicate = [];
		$unitData = "";
		$unitTo = explode ("_", $fileName)[0];
		// Define how many rows we want to read for each "chunk"
		
		foreach(array_chunk($data,count($data),true) as $rows) {
			foreach($rows as $x => $row) {
				if ($x == 0) {
					continue;
				}  
				$agendaNumber =  $row[0];
				$receiptDate =  $row[1];
				$number =  $row[2];
				$realDate =  $row[3];
				$type =  $row[4];
				$note =  $row[5];
				$from =  $row[6];
				$to =  $row[7];
				$desc =  $row[8];
				$indexNumber =  $row[9];
				$status =  "success";
				$message = "";
				if (empty($indexNumber)) {
					if(!empty($message)){
						$message .= ", ";
					}
					$message .= "No tidak boleh kosong";
					$status = "error";
				}
				if (empty($to)) {
					if(!empty($message)){
						$message .= ", ";
					}
					$message .= "Kepada/Penerima tidak boleh kosong";
					$status = "error";
				} 

				// validation mandatory
				if (empty($agendaNumber)) {
					$message = "Nomor Agenda tidak boleh kosong";
					$status = "error";
				}
				if (empty($receiptDate)) {
					if(!empty($message)){
						$message .= ", ";
					}
					$message .= "Tanggal Terima tidak boleh kosong";
					$status = "error";
				}
				
				if (empty($realDate)) {
					if(!empty($message)){
						$message .= ", ";
					}
					$message .= "Tanggal Surat tidak boleh kosong";
					$status = "error";
				}
				if (empty($type)) {
					if(!empty($message)){
						$message .= ", ";
					}
					$message .= "Sifat Surat tidak boleh kosong";
					$status = "error";
				}else{
					$typeMsg = array("segera", "sangatsegera", "biasa");

					if(!in_array(strtolower(str_replace(' ', '', $type)), $typeMsg)){
						if(!empty($message)){
							$message .= ", ";
						}
						$message .= "Kesalahan penamaan Sifat Surat $type";
						$status = "error";
					}
				} 

				if (empty($note)) {
					if(!empty($message)){
						$message .= ", ";
					}
					$message .= "Isi Ringkasan/Catatan/Perihal tidak boleh kosong";
					$status = "error";
				}

				if (empty($from)) {
					if(!empty($message)){
						$message .= ", ";
					}
					$message .= "Dari/Pengirim tidak boleh kosong";
					$status = "error";
				} 

				if (empty($desc)) {
					if(!empty($message)){
						$message .= ", ";
					}
					$message .= "Keterangan Surat tidak boleh kosong";
					$status = "error";
				}else{
					$typeMsg = array("asli", "salinan", "tembusan","biasa");

					if(!in_array(strtolower(str_replace(' ', '', $desc)), $typeMsg)){
						if(!empty($message)){
							$message .= ", ";
						}
						$message .= "Kesalahan penamaan Keteragan $desc";
						$status = "error";
					}
				} 
				
				$simpandata = [
					'agendaNumber' =>  $agendaNumber , 
					'receiptDate' => $receiptDate, 
					'number' => $number,  
					'realDate'=> $realDate,
					'type'=> $type,
					'note'=> $note,
					'from'=> $from,
					'unitTo' =>  $unitTo,					 
					'to'=> $to,
					'ket'=> $desc,
					'fileName'=> $fileName,
					'indexNumber'=> $indexNumber,
					'status' => $status,
					"message" => $message
				]; 
				$resultExcelData[] = $simpandata;  
		} 
			$this->createToTemp($resultExcelData);
		$responseData = [
			"responseData" => $this->model->validationUploadTracking(),
			"totalOriginalData" => (count($data)-1)
		]; 
		return $this->respond($responseData, 200);
		}
    }

	
	public function createToTemp($listData)
	{ 
		$isSuccess = false;
		if (!empty($listData)) { 
			$resultExcelData = [];
			$message = "";
			$status = "";
			foreach(array_chunk($listData,count($listData),true) as $rows) {
				$trackingData = $this->model->saveDataTemp($rows);
				if($trackingData){
					$response = [
						"status" => 'success',
						"message" => ""
					];
					$resultExcelData[] = $response;
				}
			}
			return $this->respond($resultExcelData, 200);
		} 
		return $this->failNotFound("Data gagal tersimpan, periksa dan coba lagi");
	}

	public function create()
	{
		$modelInbox = new ModelInbox(); 
		$modelUnknown = new ModelUnknown(); 
		$modelHistory= new ModelHistory(); 
		$modelFileUpload = new ModelFileUpload(); 

		$data = $this->request->getPost('listData');
		$isSuccess = false;
		if (!empty($data)) {
			$listData = json_decode($data); 
			$resultExcelData = [];
			$message = "";
			$status = "";

		 
				$fileUploadData = $modelFileUpload->saveFileUploadData($listData->fileUpload);
				if($fileUploadData){
					$response = [
						"type" => 'fileUploadData',
						"status" => 'success',
						"message" => "success insert data"
					];
					$resultExcelData[] = $response; 
				} 
		 
			 
				if(count($listData->nadineData) > 0){
					$nadineData = is_array($listData->nadineData) ? count($listData->nadineData) : 1;
				foreach(array_chunk($listData->nadineData,$nadineData,true) as $rows) {
				 
					$trackingData = $this->model->saveNadineData($rows);
					if($trackingData){
						$response = [
							"type" => 'nadineData',
							"status" => 'success',
							"message" => "success insert data"
						];
						$resultExcelData[] = $response; 
					} 
				}
			} 

			if(count($listData->inboxData) > 0){
				$inboxData = is_array($listData->inboxData) ? count($listData->inboxData) : 1;
				foreach(array_chunk($listData->inboxData,$inboxData,true) as $rows) {
				 
					$trackingData = $modelInbox->saveInboxData($rows);
					if($trackingData){
						$response = [
							"type" => 'inboxData',
							"status" => 'success',
							"message" => "success insert data"
						];
						$resultExcelData[] = $response; 
					} 
				}
			}
			
		 
			 if(count($listData->unknownData) > 0){
				$unknownData = is_array($listData->unknownData) ? count($listData->unknownData) : 0;
				if($unknownData > 0){
					foreach(array_chunk($listData->unknownData,$unknownData,true) as $rows) {
					 
						$trackingData = $modelUnknown->saveUnknownData($rows);
						if($trackingData){
							$response = [
								"type" => 'unknownData',
								"status" => 'success',
								"message" => "success insert data"
							];
							$resultExcelData[] = $response; 
						} 
					}
				 
				}
			 }
			 if(count($listData->historyData) > 0){
				$historyData = is_array($listData->historyData) ? count($listData->historyData) : 1;
				foreach(array_chunk($listData->historyData,$historyData,true) as $rows) {
				 
					$trackingData = $modelHistory->saveHistoryData($rows);
					if($trackingData){
						$response = [
							"type" => 'historyData',
							"status" => 'success',
							"message" => "success insert data"
						];
						$resultExcelData[] = $response; 
					} 
				}
			 } 

			return $this->respond($resultExcelData, 200); 
		} 
		return $this->failNotFound("Data gagal tersimpan, periksa dan coba lagi");
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
