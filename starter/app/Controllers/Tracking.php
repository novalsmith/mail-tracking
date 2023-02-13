<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelTracking;
use App\Models\ModelUnit;

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
		$newName = $file_excel->getName();
		$file_excel->move('../public/nadineFiles', $newName);
		$chunkSize = 50;
		$startRow = 0;
		$maxRows = 3000;
		$data = $spreadsheet->getActiveSheet()->toArray();
		// if(count($data)-1 > $maxRows){
		// 	$dataError = [
		// 		"message" => "Sorry you have limit $maxRows records, please try again. "
		// 	];
		// 	return $this->respond($dataError,404);
		// } 
		$resultExcelData = [];
		$resultExcelDataDuplicate = [];
		$unitData = "";
		$unitTo = explode ("_", $newName)[0];
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
					$status =  "success";
					$message = "";
	 
					if (empty($number)) {
						if(!empty($message)){
							$message .= ", ";
						}
						$message .= "Nomor Surat tidak boleh kosong";
						$status = "error";
					}else{

						// Do for all validation when doesn't have duplicate data
						// $numberData = $this->model->validateDumplicate($number,$unitTo);
						// if(!empty($numberData)){
						// 	if(!empty($message)){
						// 		$message .= ", ";
						// 	}
						// 	$message .= "Nomor surat $number sudah ada (Duplikasi)";
						// 	$status = "error";
						// }else{
							if (empty($to)) {
								if(!empty($message)){
									$message .= ", ";
								}
								$message .= "Kepada/Penerima tidak boleh kosong";
								$status = "error";
							}
							// else{
							// 	$unitData = $modelUnit->getUnitByPrefixNameTo($to);
							// 	if(empty($unitData)){
							// 		if(!empty($message)){
							// 			$message .= ", ";
							// 		}
							// 		$message .= "Kepada/Penerima tidak memiliki Unit (Uknown)";
							// 		$status = "info";
							// 	}
							// } 
		
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
						// }
					} 
					$simpandata = [
						'indexNumber' => rand(5,10),
						'agendaNumber' =>  $agendaNumber , 
						'receiptDate' => $receiptDate, 
						'number' => $number,  
						'realDate'=> $realDate,
						'type'=> $type,
						'note'=> $note,
						'from'=> $from,
						'unitTo' =>  $unitTo,					 
						'to'=> $to,
						'desc'=> $desc,
						'status' => $status,
						"message" => $message
					];
	
					// $db->table('siswa')->insert($simpandata);
					// session()->setFlashdata('message','Berhasil import excel');
					if(!in_array($number, $resultExcelData)){
						$filteredItems = array_filter($resultExcelData, function($elem) use($number){
							return $elem['number'] == $number;
						});
						if(count($filteredItems) == 0){
							$resultExcelData[] = $simpandata; 
						}else{ 
							$resultExcelDataDuplicate[] = $filteredItems;
						}
				}
			}
			// $a = array_unique($resultExcelData,SORT_REGULAR);
			// $duplicates= array_diff($resultExcelData $a, );
			$duplications = [];
			foreach($resultExcelDataDuplicate as $key => $value){
				foreach($resultExcelDataDuplicate[$key] as  $keys => $val){
					// $resultExcelDataDuplicate[$key][$keys]['status'] = 'info';
					$simpandata = [
						'indexNumber' => rand(5,10),
						'agendaNumber' =>  $val['agendaNumber'] , 
						'receiptDate' =>   $val['receiptDate'] , 
						'number' =>  $val['number'] , 
						'realDate'=>  $val['realDate'] , 
						'type'=>   $val['type'] , 
						'note'=>   $val['note'] , 
						'from'=>   $val['from'] , 
						'unitTo' =>  	 $val['unitTo'] , 				 
						'to'=> $val['to'] , 	
						'desc'=>   $val['desc'] , 	
						'status' => 'info', 
						"message" =>  "No.Agenda ".$val['agendaNumber']." dan No.Surat ".$val['number']." Duplikasi data pada file excel."
					];
					$duplications[] = $simpandata;
				}
				 
			}
			$responseData = [
				// "a"=> count($a),
				// "bb"=> count($resultExcelData),
				// "duplicates" => $newData,
				"responseData" => array_merge($duplications,$resultExcelData),
				// "merging" => array_merge($newData,$resultExcelData),
				"totalOriginalData" => (count($data)-1)
			];
			return $this->respond($responseData, 200);
			}
    }

	
	
	public function create()
	{
		$modelTracking = new ModelTracking(); 
		$data = $this->request->getPost('listData');
		$isSuccess = false;
		if (!empty($data)) {
			$listData = json_decode($data); 
			$resultExcelData = [];
			$message = "";
			$status = "";
			foreach(array_chunk($listData,count($listData),true) as $rows) {
				// foreach($rows as $x => $row) {
				// 	$numberData = $this->model->validateDumplicate($row->number,$row->unitTo);
				// 	if(!empty($numberData)){
				// 		if(!empty($message)){
				// 			$message .= ", ";
				// 		}
				// 		$message .= "Nomor surat $row->number sudah ada (Duplikasi)";
				// 		$status = "error";
					
				// 		$simpandata = [
				// 			'status' => $status,
				// 			"message" => $message
				// 		];
				// 		$resultExcelData[] = $simpandata;
				// 	}
				// }
				
				// if(empty($status)){
					$trackingData = $modelTracking->saveData($rows);
					if($trackingData){
						$response = [
							"status" => 'success',
							"message" => ""
						];
						$resultExcelData[] = $response;
						// return $this->respond($response);
					}
				// }
			}

			// if(!empty($status)){
				return $this->respond($resultExcelData, 200);
			// }
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
