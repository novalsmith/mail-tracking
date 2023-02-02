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
		$data = $this->model->findAll();
		return $this->respond($data, 200);
	}
	public function show($id)
	{
		// $role = $this->request->getVar('role');
		$modelData = new ModelTracking();
		$data = $modelData->getTrackingByRole($id);
	 
			return $this->respond($data, 200);
		 
	}

	public function importCsvToDbBackup()
    {
       
            if($file = $this->request->getFile('TrackingFileUpload')) {
            if ($file->isValid() && ! $file->hasMoved()) {
                $newName = $file->getRandomName();
                $file->move('../public/csvfile', $newName);
                $file = fopen("../public/csvfile/".$newName,"r");
                $i = 0;
                $numberOfFields = 2;
                $csvArr = array();
				$resultData = array();
                
                while (($filedata = fgetcsv($file, 400, ";")) !== FALSE) {
                    $num = count($filedata);
                    if($i > 0 && $num == $numberOfFields){ 
                        $csvArr[$i]['agendaNumber'] = $filedata[0];
                        $csvArr[$i]['receiptDate'] = $filedata[1];
                        // $csvArr[$i]['number'] = $filedata[2];
                        // $csvArr[$i]['realDate'] = $filedata[3];
						// $csvArr[$i]['type'] = $filedata[4];
						// $csvArr[$i]['note'] = $filedata[5];
						// $csvArr[$i]['from'] = $filedata[6];
						// $csvArr[$i]['to'] = $filedata[7];
						// $csvArr[$i]['description'] = $filedata[8];
						
                    }
                    $i++;
					$resultData[] =   $csvArr;
                }
                fclose($file);
                $count = 0;
			
			 
			 	return $this->respond($resultData, 200);
				
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
	public function importCsvToDb()
    {
		$modelUnit = new ModelUnit();
		$file_excel = $this->request->getFile('TrackingFileUpload');
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
		// Define how many rows we want to read for each "chunk"
		
			foreach(array_chunk($data,count($data),true) as $rows) {
				foreach($rows as $x => $row) {
					if ($x == 0) {
						continue;
						// $agendaNumberHeader =  $rows[$x][0];
						// $receiptDateHeader =  $rows[$x][1];
						// $numberHeader =  $rows[$x][2];
						// $realDateHeader =  $rows[$x][3];
						// $typeHeader =  $rows[$x][4];
						// $noteHeader =  $rows[$x][5];
						// $fromHeader =  $rows[$x][6];
						// $toHeader =  $rows[$x][7];
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
	
					
					if (empty($to)) {
						if(!empty($message)){
							$message .= ", ";
						}
						$message .= "Kepada/Penerima tidak boleh kosong";
						$status = "error";
					}
					else{
						$unitData = $modelUnit->getUnitByPrefixNameTo($to);
						if(empty($unitData)){
							if(!empty($message)){
								$message .= ", ";
							}
							$message .= "Kepada/Penerima tidak memiliki Unit (Uknown)";
							$status = "info";
						}
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
					if (empty($number)) {
						if(!empty($message)){
							$message .= ", ";
						}
						$message .= "Nomor Surat tidak boleh kosong";
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
					
					// else{
					// 	$unitData = $modelUnit->getUnitByPrefixNameFrom($from);a
					// 	if(empty($unitData)){
					// 		if(!empty($message)){
					// 			$message .= ", ";
					// 		}
					// 		$message .= "Dari/Pengirim tidak memiliki Unit (Uknown)";
					// 		$status = "error";
					// 	}
					// }
	
					

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
						'indexNumber' => rand(5,10),
						'agendaNumber' =>  $agendaNumber , 
						'receiptDate' => $receiptDate, 
						'number' => $number, 
						'type'=> $type,
						'realDate'=> $realDate,
						'type'=> $type,
						'note'=> $note,
						'from'=> $from,
						'to' => $to,
						'to'=> [
							'code' => $unitData,
							'name'=> $to
						],
						'desc'=> $desc,
						'status' => $status,
						"message" => $message
					];
	
					// $db->table('siswa')->insert($simpandata);
					// session()->setFlashdata('message','Berhasil import excel');
					// if(!in_array($number, $resultExcelData['number'])){
						$filteredItems = array_filter($resultExcelData, function($elem) use($number){
							return $elem['number'] == $number;
						});
						if(count($filteredItems) == 0){
							$resultExcelData[] = $simpandata; 
						}
				}
			}
			return $this->respond($resultExcelData, 200);
		
    }

	
	
	public function create()
	{
		$modelTracking = new ModelTracking(); 
		$data = $this->request->getPost('listData');
		$isSuccess = false;
		if (!empty($data)) {
			$countData = json_decode($data); 
			foreach(array_chunk($countData,count($countData),true) as $rows) {
				$trackingData = $modelTracking->saveData($rows);
				if($trackingData){
					$response = [
						'status' => 200,
						'error' => null,
						'messages' => "Data Nadine Berhasil tersimpan"
					];
					return $this->respond($response);
				}
			}
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
