<?php

/**
 * 核心控制器
 * @copyright   Copyright(c) 2014
 * @author      Aaron <aaron920726@163.com>
 * @version     1.0
 */
class Controller {

  public function __construct() {
    header('Content-type:text/html;chartset=utf-8');
  }

  /**
   * 实例化模型
   * @access      final   protected
   * @param       string  $model  模型名称
   */
  final protected function model($model) {
    if (empty($model)) {
      trigger_error('不能实例化空模型');
    }
    $model_name = $model . 'Model';
    return new $model_name();
  }

  //$auto 是否是载入文件 还是载入且实例化类
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

  protected function showTemplate($path, $data = array()) {
    $template = $this->load('Template', $auto = TRUE);
    $template->init($path, $data);
    $template->outPut();
  }

  final protected function config($config) {
    return Application::$_config[$config];
  }

}
