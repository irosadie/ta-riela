<?php

namespace app\utils\encrypt;

class Log
{
    public $table;
    public $activity;
    public $data_inserted;
    public $data_before;

    function __construct($table=null, $activity=null, $data_inserted=null, $data_before=null){
        $this->table = $table;
        $this->activity = $activity; 
        $this->data_inserted = $data_inserted;
        $this->data_before = $this->data_before;
        $this->save();
    }

    protected function save(){
        $model = new Logs();
        $model->table = $this->table;
        $model->activity = $this->activity;
        $model->data_inserted = $this->data_inserted; 
        $model->data_before = $this->data_before;
        $model->save(false);
        return true;
    }
}