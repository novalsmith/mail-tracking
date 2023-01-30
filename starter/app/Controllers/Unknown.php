<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelUnknown;

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
	public function create()
	{
		 // todo
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
