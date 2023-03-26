<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelInbox;
use App\Models\ModelHistory;
use App\Models\ModelOutbox;


class History extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$this->model = new ModelHistory();
	}
	 
	public function index()
	{ 
		$trackingId = $this->request->getVar('trackingId');
		$data = $this->model->getHistoryHeader($trackingId);
		return $this->respond($data, 200);
	} 
}
