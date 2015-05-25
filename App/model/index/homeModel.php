<?php

class homeModel extends Model {

  protected $table_name = 'menu';
  protected $primary_key = 'id';

  public function querys() {
    $data = array('id' => 10, 'sort' => 10);
    //$data = "`id`= 10 and `sort`= 6";
    if (!$this->db->deleteByWhere($data)) {
      echo "删除失败！";
    }
  }

}
