<?php

class indexController extends Controller {

  public function __construct() {
    parent::__construct();
  }
  public function newList() {
    //echo "new/index/newlist 新闻页面";
    $data = array(
        'as'=>123456,
        'b'=>2,
    );
    
//    $this->showTemplate('new/index/newlist',$data);
  }
  public function index(){
    echo "<br/>模块：".__CLASS__."<br/>";
    echo "方法：".__FUNCTION__;
  }

  

}
