<?php

/**
 * 应用驱动类
 * @copyright   Copyright(c) 2014
 * @author      aaron
 * @version     1.0
 */
class Application {

  public static $_config = null;
  public static $_lib = null;

  /**
   * 载入控制器和模型
   */
  public static function init() {
    self::setAutoLibs();
    require SYS_CORE_PATH . '/model.php';
    require SYS_CORE_PATH . '/controller.php';
  }

  /**
   * 载入项目
   */
  public static function run($config) {
    session_start();
    self::$_config = $config['system'];
    self::init();
    self::autoload();
    self::$_lib['router']->setUrlType(self::$_config['route']['url_type']);
    $url_Arr = self::$_lib['router']->getUrlArray();  //返回路由
    self::routeToCm($url_Arr);                    //路由跳转
  }

  /**
   * 根据URL分发到Controller和Model
   * @access      public 
   * @param       array   $url_array     
   */
  public static function routeToCm($url_array = array()) {
    $app = '';
    $controller = '';
    $action = '';
    $model = '';
    $params = '';
    $app = isset($url_array['app']) ? $url_array['app'] : self::$_config['route']['defalut_group'];
    if (isset($url_array['m'])) {
      $controller = $model = $url_array['m'];
      //不存在app参数
      $controller_file = CONTROLLER_PATH . '/' . $app . '/' . $controller . 'Controller.php';
      $model_file = MODEL_PATH . '/' . $app . '/' . $model . 'Model.php';
    } else {
      $controller = $model = self::$_config['route']['default_controller'];
      if ($app) {
        $controller_file = CONTROLLER_PATH . '/' . $app . '/' . $controller . 'Controller.php';
        $model_file = MODEL_PATH . '/' . $app . '/' . $model . 'Model.php';
      } else {
        $controller_file = CONTROLLER_PATH . '/' . $controller . 'Controller.php';
        $model_file = MODEL_PATH . '/' . $model . 'Model.php';
      }
    }
    if (isset($url_array['ac'])) {
      $action = $url_array['ac'];
    } else {
      $action = self::$_config['route']['default_action'];
    }
    if (isset($url_array['params'])) {
      $params = $url_array['params'];
    }
    if (!is_dir(CONTROLLER_PATH . '/' . $app)) {
      Debug::addmsg($app . '分组不存在！');
    }
    //开始载入
    if (file_exists($controller_file)) {
      if (file_exists($model_file)) {
        //载入模型
        require $model_file;
      }
      //载入控制器 并且实例化 
      require $controller_file;
      $controller = $controller . 'Controller';
      $controller = new $controller;
      if ($action) {
        //判断该控制器是否有该方法
        if (method_exists($controller, $action)) {
          isset($params) ? $controller->$action($params) : $controller->$action();
        } else {
          Debug::addmsg($action . '方法不存在！');
        }
      } else {
        Debug::addmsg($controller . '控制器方法不存在!');
      }
    } else {
      Debug::addmsg($controller . '控制器不存在!');
    }
  }

  /* public function __autoload($class) {
    if (in_array($class, array('route', 'mysql', 'template'))) {
    require SYS_LIB_PATH . '/lib_'.$class.'.php';
    }else{

    }
    } */

  /**
   * 加载基础类库
   */
  public static function autoload() {
    foreach (self::$_lib as $key => $value) {
      require (self::$_lib[$key]);
      $lib = ucfirst($key);
      self::$_lib[$key] = new $lib;
      Debug::addmsg("<b>$key</b>类", 1);
    }
  }

  /**
   * 自动加载的类库
   * @access      public 
   */
  public static function setAutoLibs() {
    self::$_lib = self::$_config['autolib'];
  }

}

?>