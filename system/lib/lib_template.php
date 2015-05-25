<?php

/**
 * 模版处理类
 */
final class Template {

  public $template_name = null;
  public $data = array();
  public $out_put = null;

  public function init($path, $data) {
    $this->template_name = $path;
    $this->data = $data;
    $this->fetch();
  }

  /**
   * 加载模版文件
   */
  public function fetch() {
    $view_file = VIEW_PATH . '/' . $this->template_name . '.php';
    if (file_exists($view_file)) {
      extract($this->data);
      ob_start();
      include $view_file;
      $content = ob_get_contents();
      ob_clean();
      $this->out_put = $content;
    } else {
      trigger_error('加载 ' . $view_file . ' 模板不存在');
    }
  }

  /**
   * 输出模版
   */
  public function outPut() {
    echo $this->out_put;
  }

}
