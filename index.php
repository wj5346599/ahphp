<?php

/**
 * 入口文件
 */
header("Content-Type:text/html;charset=utf-8");  //设置系统的输出字符为utf-8
date_default_timezone_set("PRC");       //设置时区（中国）
define('SYSTEM_PATH', dirname(__FILE__) . '/system');
define('ROOT_PATH', substr(SYSTEM_PATH, 0, -7));
define('APP_PATH', '/App');
define('SYS_LIB_PATH', SYSTEM_PATH . '/lib');
define('APP_LIB_PATH', ROOT_PATH . '/lib');
define('SYS_CORE_PATH', SYSTEM_PATH . '/core');
define('CONTROLLER_PATH', ROOT_PATH . APP_PATH . '/controller');
define('MODEL_PATH', ROOT_PATH . APP_PATH . '/model');
define('VIEW_PATH', ROOT_PATH . APP_PATH . '/view');
define('LOG_PATH', ROOT_PATH . '/error/');
define('DEBUG', true);
if (defined("DEBUG") && DEBUG == 1) {
  $GLOBALS["debug"] = 1;                 //初始化开启debug
  error_reporting(E_ALL ^ E_NOTICE);   //输出除了注意的所有错误报告
  include SYS_LIB_PATH . "/lib_debug.php";  //包含debug类
  Debug::start();                               //开启脚本计算时间
  set_error_handler(array("Debug", 'Catcher')); //设置捕获系统异常
} else {
  ini_set('display_errors', 'Off');   //屏蔽错误输出
  ini_set('log_errors', 'On');              //开启错误日志，将错误报告写入到日志中
  ini_set('error_log', ROOT_PATH . '/runtime/error.log'); //指定错误日志文件
}
require SYSTEM_PATH . '/app.php';
require dirname(__FILE__) . '/config/config.php';
require dirname(__FILE__) . '/common/functions.php';
Application::run($CONFIG);

//设置输出Debug模式的信息
if (defined("DEBUG") && DEBUG == 1 && $GLOBALS["debug"] == 1) {
  Debug::stop();
  Debug::message();
}