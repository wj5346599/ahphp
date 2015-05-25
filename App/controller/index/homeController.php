<?php

class homeController extends Controller {

  public function __construct() {
    parent::__construct();
  }

  public function start() {
    $model = $this->Model('home');
    $rel = $model->querys();
    $this->showTemplate('new/index/newlist');
  }

  public function index() {
    echo "<br/>模块：" . __CLASS__ . "<br/>";
    echo "方法：" . __FUNCTION__;
  }

  public function caches() {
    $a = $this->load('cache');
    $a->init('cache/runtime');
//    $data = array('a' => 1, 'b' => 2);
//    $a->set('aaron', $data);
//    $a->flushCache();
  }

}
