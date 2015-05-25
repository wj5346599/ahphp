<?php

/**
 * Url处理类
 */
final class Router {

  public $url_query;
  public $url_type;
  public $route_url = array();

  /**
   * 赋值到url_query属性
   */
  public function __construct() {
    $this->url_query = parse_url($_SERVER['REQUEST_URI']);
  }

  /**
   * 指定路由模式 1query 2 pathinfo
   */
  public function setUrlType($url_type = 2) {

    if ($url_type > 0 && $url_type < 4) {
      $this->url_type = $url_type;
    } else {
      trigger_error('指定的路由类型不存在！');
    }
  }

  public function getUrlArray() {
//选择模式执行url
    $this->makeUrl();
    return $this->route_url;
  }

  public function makeUrl() {
    switch ($this->url_type) {
      case 1:
        $this->queryToArr();
        break;
      case 2:
        $this->pathinfoToArr();
        break;
      case 3:
        $this->threeToArr();
        break;
    }
  }

  /**
   * 将query的URL形式转化为数组
   * @access      public 
   */
  protected function queryToArr() {
    $arr = !empty($this->url_query['query']) ? explode('&', $this->url_query['query']) : array();
    $array = $tmp = array();
    if (count($arr) > 0) {
      foreach ($arr as $item) {
        $tmp = explode('=', $item);
        $array[$tmp[0]] = $tmp[1];
      }
      if (isset($array['app'])) {
        $this->route_url['app'] = $array['app'];
      }
      if (isset($array['m'])) {
        $this->route_url['m'] = $array['m'];
      }
      if (isset($array['ac'])) {
        $this->route_url['ac'] = $array['ac'];
      }
      if (count($array) > 0) {
        $this->route_url['params'] = $array;
      } else {
        $this->route_url = array();
      }
      return $this->route_url;
    }
  }

  /**
   * 将PATH_INFO的URL形式转化为数组
   * @access      public
   */
  protected function pathinfoToArr() {
    if (isset($_SERVER['PATH_INFO'])) {
      $arr = !empty($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : '';
      $paths = array_filter(explode('/', trim($_SERVER['PHP_SELF'], '/')));
      if (count($arr) > 2) {
        if (isset($arr[0])) {
          $this->route_url['app'] = $arr[0];
          array_shift($arr);
        }if (isset($arr[0])) {
          $this->route_url['m'] = $arr[0];
          array_shift($arr);
        }
        if (isset($arr[0])) {
          $this->route_url['ac'] = $arr[0];
          array_shift($arr);
        }
      } else {
        if (isset($arr[0])) {
          $this->route_url['m'] = $arr[0];
          array_shift($arr);
        }
        if (isset($arr[0])) {
          $this->route_url['ac'] = $arr[0];
          array_shift($arr);
        }
      }
      if (isset($_SERVER['QUERY_STRING'])) {
        $this->route_url['params'] = $arr;
      }
      return $this->route_url;
    }
  }

  protected function threeToArr() {
    if (isset($_SERVER['PATH_INFO'])) {
      $arr = !empty($_SERVER['PATH_INFO']) ? explode('/', trim($_SERVER['PATH_INFO'], '/')) : '';
      $paths = array_filter(explode('/', trim($_SERVER['PHP_SELF'], '/')));
      if (count($arr) > 2) {
        if (isset($arr[0])) {
          $this->route_url['app'] = $arr[0];
          array_shift($arr);
        }if (isset($arr[0])) {
          $this->route_url['m'] = $arr[0];
          array_shift($arr);
        }
        if (isset($arr[0])) {
          $this->route_url['ac'] = $arr[0];
          array_shift($arr);
        }
      } else {
        if (isset($arr[0])) {
          $this->route_url['m'] = $arr[0];
          array_shift($arr);
        }
        if (isset($arr[0])) {
          $this->route_url['ac'] = $arr[0];
          array_shift($arr);
        }
        $arr = !empty($this->url_query['query']) ? explode('&', $this->url_query['query']) : array();
        if (!empty($arr)) {
          $this->route_url['params'] = $arr;
        }
      }
    }
    pe($this->route_url);exit;
    return $this->route_url;
  }

}
