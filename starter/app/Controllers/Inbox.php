<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Models\ModelInbox;

class Inbox extends BaseController
{
	use ResponseTrait;
	function __construct()
	{
		$this->model = new ModelInbox();
	}
	 
	public function show($id)
	{
		$data = $this->model->getInboxByRole($id);
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
