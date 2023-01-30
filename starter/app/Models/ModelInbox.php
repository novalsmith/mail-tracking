<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelInbox extends Model
{
    protected $table = "v_inbox";
    protected $primaryKey = "trackingid";
    protected $allowedFields = [
        'trackingid','agendaNumber','receiptDate','number',
    'realDate','type','note','from','to','description'];

    function getInboxByRole($role)
    {
        $builder = $this->table("v_inbox")->where("to", $role);
       $data =  $builder->get()->getResult(); 
        return $data;
    }
 
}
