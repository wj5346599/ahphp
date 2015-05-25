<?php

/**
 * pdo 数据连接
 */
class Ahpdo {

  private $db_host; //数据库主机
  private $db_user; //数据库用户名
  private $db_password; //数据库用户名密码
  private $db_database; //数据库名
  private $_condition = array(); //是否长连接
  private $pdo; //连接资源
  private $_table = null; //数据库表名
  private $_primary = 'id'; //主键ID
  private $result; //执行query命令的结果资源标识
  private $sql; //sql执行语句
  private $row; //返回的条目数
  private $coding; //数据库编码，GBK,UTF8,gb2312
  private $bulletin = true; //是否开启错误记录
  private $show_error = true; //测试阶段，显示所有错误,具有安全隐患,默认关闭
  private $is_error = false; //发现错误是否立即终止,默认true,建议不启用，因为当有问题时用户什么也看不到是很苦恼的

  public function __construct() {
    $config_db = Application::$_config['db'];
    $this->db_host = $config_db['db_host'];
    $this->db_user = $config_db['db_user'];
    $this->db_pwd = $config_db['db_password'];
    $this->db_database = $config_db['db_database'];
    $this->db_table_prefix = $config_db['db_table_prefix'];
    $this->db_charset = $config_db['db_charset'];
    $this->pdo_connect();
  }

  //主键和表名赋值
  public function init($table_name, $primary_key) {
    $this->_table = $table_name;
    $this->_primary = $primary_key;
  }

  /**
   * PDO连接
   */
  private function pdo_connect() {
    try {
      $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION; //错误捕获异常
      $pdo_options[PDO::ATTR_PERSISTENT] = $this->_condition; //短连接
      //pdo连接赋值
      $this->pdo = new PDO("mysql:localhost={$this->db_host};dbname={$this->db_database}", $this->db_user, $this->db_password, $pdo_options);
      $this->pdo->exec("set names $this->db_charset");
    } catch (Exception $e) {
      return $this->setExceptionError($e->getMessage(), $e->getline(), $e->getFile());
    }
  }

  /**
   * 开启事务
   */
  private function begin() {

    $this->beginTransaction = $this->pdo->beginTransaction();
    return $this->beginTransaction;
  }

  /**
   * 事务提交
   */
  private function commit() {

    $this->commit = $this->pdo->commit();
    return $this->commit;
  }

  /**
   * 回滚事务
   */
  private function rollback() {

    $this->rollback = $this->pdo->rollback();
    return $this->rollback;
  }

  /**
   * 获取所有字段和记录
   * 
   */
  function selectAll($sql, $array = '') {
    $pdo = $this->pdo;
    try {
      $stmt = $pdo->prepare($sql);
      if (!empty($array) && is_array($array)) {
        $stmt->execute($array);
      } else {
        $stmt->execute();
      }
    } catch (Exception $e) {
      return $this->setExceptionError($e->getMessage(), $e->getLine(), $e->getFile());
    }
    return $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 
   * @param intval $start limit开始
   * @param intval $limit limit结果数
   * @param array  $field   查询字段
   * @return array 数组结果集
   * 
   */
  public function selectBylimit($start, $limit, $field, $_where) {
    $pdo = $this->pdo;
    $field = '*';
    if (is_array($field) && !empty($field)) {
      $field = join(',', $field);
    }
    $sql = "SELECT $field FROM {$this->db_table_prefix}{$this->_table}";
    if (!empty($_where)) {
      $where = $_where;
    }
    if (is_array($_where)) {
      $where = $this->getCodeByWhere($_where);
    }
    $sql .= ' where ' . $where;
    if ($start != 0 || $limit != 0) {
      $sql .= " limit $start,$limit";
    }
    try {
      $stmt = $pdo->query($sql);
    } catch (Exception $e) {
      return $this->setExceptionError($e->getMessage(), $e->getLine(), $e->getFile());
    }
    return $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  /**
   * 删除数据 直接传入主键值
   * @param int $id 主键value
   * @return int 影响值		
   */
  public function delete($id) {
    if (!isset($this->_primary) || empty($this->_primary)) {
      die("primary_key should be not empty!");
    }
    try {
      $sql = "DELETE FROM {$this->db_table_prefix}{$this->_table} WHERE {$this->_primary} = $id";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
    } catch (Exception $e) {
      return $this->setExceptionError($e->getMessage(), $e->getLine(), $e->getFile());
    }
    return $stmt->rowcount();
  }

  /**
   * 删除数据 直接传入主键值
   * @param array or string $condition 
   * example:
   * 1、$data = array('id'=>10,'sort'=>6);
   * 2、$data = "`id`= 10 and `sort`= 6";
   */
  public function deleteByWhere($condition) {
    if (is_array($condition)) {
      $condition = $this->getCodeByWhere($condition);
    }
    try {
      $sql = "DELETE FROM {$this->db_table_prefix}{$this->_table} Where $condition";
      $stmt = $this->pdo->prepare($sql);
      $stmt->execute();
    } catch (Exception $e) {
      return $this->setExceptionError($e->getMessage(), $e->getLine(), $e->getFile());
    }
    return $stmt->rowcount();
  }

  /**
   * 添加数据
   * $data = array('pid' => '0', 'typename' => '吴江', 'create_time' => time());
   * $this->db->insert($data);
   */
  public function insert($saveData) {
    try {
      $sql = "INSERT INTO {$this->db_table_prefix}{$this->_table} SET ";
      $field = $this->getCodeByAdd($saveData);
      $sql .=$field;
      $this->pdo->exec($sql);
    } catch (Exception $e) {
      return $this->setExceptionError($e->getMessage(), $e->getLine(), $e->getFile());
    }
    return $this->pdo->lastInsertId();
  }

  /**
   * 取结果总集
   */
  protected function rowCount() {
    
  }

  /**
   *  插入数据 添加方法
   */
  private function getCodeByAdd($args) {
    $code = '';
    if (is_array($args)) {
      foreach ($args as $k => $v) {
        if ($v == '') {
          continue;
        }
        $code .="`$k`='$v',";
      }
    }
    $code = trim($code, ',');
    return $code;
  }

  /**
   * 数组转字符串 方便操作
   * 适用where条件下的数组操作
   */
  private function getCodeByWhere($args) {
    $code = '';
    if (is_array($args)) {
      foreach ($args as $k => $v) {
        if ($v == '') {
          continue;
        }
        $code .="`$k`='$v' And ";
      }
    }
    $code = substr($code, 0, -4);
    return $code;
  }

  /**
   * 销毁连接资源
   */
  function __destruct() {
    $this->pdo = null;
  }

  /**
   * 错误提示信息
   */
  private function setExceptionError($getMessage, $getLine, $getFile) {

    echo "Error message is " . $getMessage . "<br /> The Error in " . $getLine . " line <br /> This file dir on " . $getFile;
    exit();
  }

}
