<?php

/**
 * 模型基础类
 */
class Model {

  protected $db = null;

  public function __construct() {
    header('Content-type:text/html;chartset=utf-8');
    $this->db = $this->load('ahpdo');
    $config_db = $this->config('db');
    //初始话数据库类
    $this->db->init($this->table_name,  $this->primary_key);
  }

  protected function load($lib, $auto = TRUE) {
    if (empty($lib)) {
      trigger_error("加载的类不能为空");
    } else {
      $lib_file = SYS_LIB_PATH . "/lib_" . $lib . ".php";
      if (file_exists($lib_file)) {
        require $lib_file;
        if ($auto) {
          $lib_class = ucfirst($lib);
          return new $lib_class();
        }
      } else {
        die("不存在该文件：" . $lib_file);
      }
    }
  }

  final protected function config($config) {
    return Application::$_config[$config];
  }

}
