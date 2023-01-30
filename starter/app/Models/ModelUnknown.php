<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUnknown extends Model
{
    protected $table = "v_unknown";
    protected $primaryKey = "trackingid";
    protected $allowedFields = [
        'trackingid','agendaNumber','receiptDate','number',
    'realDate','type','note','from','to','description'];

    function getUnknown()
    {
        $builder = $this->table("v_inbox");
       $data =  $builder->get()->getResult(); 
        return $data;
    }
 
}
