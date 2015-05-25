<?php

/**
 * 缓存类
 */
final class Cache {

  private $cache_dir = null;
  private $cache_prefix = null;
  private $cache_time = null;
  private $cache_mode = null;

  /**
   * 
   * @param type 缓存目录
   * @param type 缓存前缀
   * @param type 缓存过期时间
   * @param type 缓存模式 1、序列号|2、普通
   */
  function init($cache_dir = 'cache', $cache_prefix = 'cache_', $cache_time = 1800, $cache_mode = 2) {
    $this->cache_dir = $cache_dir;
    $this->cache_prefix = $cache_prefix;
    $this->cache_time = $cache_time;
    $this->cache_mode = $cache_mode;
  }

  /**
   * 设置缓存
   * @param type $id 缓存名
   * @param type $data 缓存内容
   */
  function set($id, $data) {
    if (!isset($id)) {
      return false;
    }
    //得到缓存文件绝对路径 和 数据
    $cache = array(
        'file' => $this->getFileName($id),
        'data' => $data,
    );
    $this->writeCache($cache);
  }

  /**
   * 读取缓存
   * @param type $id 缓存名
   */
  function get($id) {
    if (!$this->hasCache($id)) {
      return false;
    }
    $data = $this->getCacheData($id);
    return $data;
  }

  /**
   * 判断缓存类型
   * @param type $data 缓存数据 包含 file路径和data
   * @return boolean
   */
  function writeCache($data = array()) {
    if (!is_dir($this->getCacheDir())) {
      mkdir($this->getCacheDir(), 0777);
    } elseif (!is_writable($this->getCacheDir())) {
      chmod($this->getCacheDir(), 0777);
    }
    if ($this->cache_mode == 1) {
      $content = serialize($data['data']);
    } else {
      $content = "<?php\n" .
              "return " .
              var_export($data['data'], true) .
              ";\n";
    }
    //是否打开成功
    if ($fp = @fopen($data['file'], w)) {
      //锁定
      @flock($fp, LOCK_EX);
      //写入
      if (fwrite($fp, $content) === false) {
        trigger_error('缓存写入失败!');
      }
      @flock($fp, LOCK_UN); //释放
      @fclose($fp); //关闭文件
      @chmod($data['file'], 0777);
      return TRUE;
    } else {
      trigger_error('打开 ' . $data['file'] . ' 失败！');
      return FALSE;
    }
  }

  /**
   * 判断是否有缓存
   * @param type $id 缓存key
   * @return boolean
   */
  public function hasCache($id) {
    if (file_exists($this->getFileName($id))) {
      //判断缓存是否过去
      if (time() - filemtime($this->getFileName($id)) > $this->cache_time) {
        unlink($this->getFileName($id));
      }
    }
    return file_exists($this->getFileName($id)) ? TRUE : FALSE;
  }

  /**
   * 获取缓存目录
   * @return string 
   */
  public function getCacheDir() {
    return $cache_dir = trim($this->cache_dir, '/');
  }

  /**
   * 获取完整缓存文件名称
   * @param string $id 缓存名
   * @return string 文件名
   */
  public function getFileName($id) {
    return $this->getCacheDir() . '/' . $this->cache_prefix . $id . '.php';
  }

  /**
   * 通过id获取缓存内容
   * @param type $id 缓存 key 
   * @return boolean
   */
  function getCacheData($id) {
    if (!$this->hasCache($id)) {
      return false;
    }
    if ($this->cache_mode == 1) {
      $fp = @fopen($this->getFileName($id), r);
      $data = @fread($fp, filesize($this->getFileName($id))); //读取内容 大小filesize获取
      @fclose($fp);
      return unserialize($data);
    } else {
      //因为inlcude里面是return 所以直接引入则return了。
      return include $this->getFileName($id);
    }
  }

  /**
   * 根据缓存文件返回缓存名称
   * @param type $file 缓存文件路径
   */
  public function getCacheName($file) {
    if (!file_exists($file)) {
      return FALSE;
    }
    $filename = basename($file);
    preg_match('/^' . $this->cache_prefix . '(.*).php$/i', $filename, $matches);
    return $matches[1];
  }

  /**
   * 删除一条缓存 根据key
   * @param type $id 缓存key
   * @return boolean
   */
  public function deleteCache($id) {
    if ($this->hasCache($id)) {
      return unlink($this->getFileName($id));
    } else {
      trigger_error('缓存不存在！');
    }
  }

  /**
   * 清除缓存
   * @return boolean
   */
  public function flushCache() {
    //glob 匹配该文件夹下 cache_*的文件
    $glob = @glob($this->getCacheDir() . '/' . $this->cache_prefix . '*.php');
    if ($glob) {
      foreach ($glob as $item) {
        $id = $this->getCacheName($item);
        $this->deleteCache($id);
      }
    }
    return TRUE;
  }

}
