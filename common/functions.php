<?php

/**
 * 公共方法
 */
function ee($msg) {
  pe($msg);
  exit;
}

function pe($msg) {
  echo '<pre>';
  print_r($msg);
  echo '</pre>';
}

function he($msg) {
  echo "<!--\n";
  pe($msg);
  echo "\n-->";
}

function execute_time() {
  static $start_time;
  if ($start_time) {
    return sprintf('%0.5f', microtime(true) - $start_time);
  }
  $start_time = microtime(true);
  return 0;
}

execute_time();

function timenow() {
  static $time_now;
  if ($time_now) {
    return $time_now;
  }

  $time_now = time();
  return $time_now;
}

function mkdirs($dir, $mode = 0777) {
  if (is_dir($dir) || @mkdir($dir, $mode))
    return TRUE;

  if (!mkdirs(dirname($dir), $mode))
    return FALSE;

  return @mkdir($dir, $mode);
}

function date_friendly($timestamp, $time_now = null, $time_limit = 1500000000, $out_formate = 'Y-m-d H:i:s', $formats = null) {
  if ($formats == null) {
    $formats = array(
        'YEAR' => '%s年前',
        'MONTH' => '%s月前',
        'DAY' => '%s天前',
        'HOUR' => '%s小时前',
        'MINUTE' => '%s分钟前',
        'SECONDS' => '%s秒前',
        'NOW' => '刚刚'
    );
  }

  $time_now = $time_now === null ? time() : $time_now;
  $seconds = $time_now - $timestamp;
  if ($time_limit != null && $seconds > $time_limit) {
    return date($out_formate, $timestamp);
  }

  $minutes = floor($seconds / 60);
  $hours = floor($minutes / 60);
  $days = floor($hours / 24);
  $months = floor($days / 30);
  $years = floor($months / 12);

  if ($years > 0) {
    $diffFormat = 'YEAR';
  } else {
    if ($months > 0) {
      $diffFormat = 'MONTH';
    } else {
      if ($days > 0) {
        $diffFormat = 'DAY';
      } else {
        if ($hours > 0) {
          $diffFormat = 'HOUR';
        } else {
          if ($minutes > 0) {
            $diffFormat = 'MINUTE';
          } else {
            $diffFormat = $seconds > 0 ? 'SECOND' : 'NOW';
          }
        }
      }
    }
  }

  $dateDiff = null;

  switch ($diffFormat) {
    case 'YEAR':
      $dateDiff = sprintf($formats[$diffFormat], $years);
      break;
    case 'MONTH':
      $dateDiff = sprintf($formats[$diffFormat], $months);
      break;
    case 'DAY':
      $dateDiff = sprintf($formats[$diffFormat], $days);
      break;
    case 'HOUR':
      $dateDiff = sprintf($formats[$diffFormat], $hours);
      break;
    case 'MINUTE':
      $dateDiff = sprintf($formats[$diffFormat], $minutes);
      break;
    case 'SECOND':
      $dateDiff = sprintf($formats[$diffFormat], $seconds);
      break;
    case 'NOW':
      $dateDiff = sprintf($formats[$diffFormat], $seconds);
      break;
  }
  return $dateDiff;
}

function valid_ip($ip) {
  $ip_segments = explode('.', $ip);

  // Always 4 segments needed
  if (count($ip_segments) != 4) {
    return FALSE;
  }
  // IP can not start with 0
  if (substr($ip_segments[0], 0, 1) == '0') {
    return FALSE;
  }
  // Check each segment
  foreach ($ip_segments as $segment) {
    // IP segments must be digits and can not be 
    // longer than 3 digits or greater then 255
    if (preg_match("/[^0-9]/", $segment) OR $segment > 255 OR strlen($segment) > 3) {
      return FALSE;
    }
  }

  return TRUE;
}

//获取ip地址
function ip() {
  if ($_SERVER['HTTP_X_FORWARDED_FOR'] AND valid_ip($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else if ($_SERVER['REMOTE_ADDR'] AND $_SERVER['HTTP_CLIENT_IP']) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
  } else if ($_SERVER['REMOTE_ADDR']) {
    $ip_address = $_SERVER['REMOTE_ADDR'];
  } else if ($_SERVER['HTTP_CLIENT_IP']) {
    $ip_address = $_SERVER['HTTP_CLIENT_IP'];
  }

  if ($ip_address === FALSE) {
    $ip_address = '0.0.0.0';
    return $ip_address;
  }

  if (strstr($ip_address, ',')) {
    $x = explode(',', $ip_address);
    $ip_address = end($x);
  }

  return $ip_address;
}

function timezone() {
  return timezone_name_get();
}

function header_httpcode($http_code = 200) {
  $http_code_data = array();

  $http_code_data[100] = 'Continue';
  $http_code_data[101] = 'Switching Protocols';
  $http_code_data[102] = 'Processing';
  $http_code_data[200] = 'OK';
  $http_code_data[201] = 'Created';
  $http_code_data[202] = 'Accepted';
  $http_code_data[203] = 'Non-Authoriative Information';
  $http_code_data[204] = 'No Content';
  $http_code_data[205] = 'Reset Content';
  $http_code_data[206] = 'Partial Content';
  $http_code_data[207] = 'Multi-Status';
  $http_code_data[300] = 'Multiple Choices';
  $http_code_data[301] = 'Moved Permanently';
  $http_code_data[302] = 'Found';
  $http_code_data[303] = 'See Other';
  $http_code_data[304] = 'Not Modified';
  $http_code_data[305] = 'Use Proxy';
  $http_code_data[306] = 'Unused';
  $http_code_data[307] = 'Temporary Redirect';
  $http_code_data[400] = 'Bad Request';
  $http_code_data[401] = 'Unauthorized';
  $http_code_data[402] = 'Payment Granted';
  $http_code_data[403] = 'Forbidden';
  $http_code_data[404] = 'File Not Found';
  $http_code_data[405] = 'Method Not Allowed';
  $http_code_data[406] = 'Not Acceptable';
  $http_code_data[407] = 'Proxy Authentication Required';
  $http_code_data[408] = 'Request Time-out';
  $http_code_data[409] = 'Conflict';
  $http_code_data[410] = 'Gone';
  $http_code_data[411] = 'Length Required';
  $http_code_data[412] = 'Precondition Failed';
  $http_code_data[413] = 'Request Entity Too Large';
  $http_code_data[414] = 'Request-URI Too Large';
  $http_code_data[415] = 'Unsupported Media Type';
  $http_code_data[416] = 'Requested range not satisfiable';
  $http_code_data[417] = 'Expectation Failed';
  $http_code_data[422] = 'Unprocessable Entity';
  $http_code_data[423] = 'Locked';
  $http_code_data[424] = 'Failed Dependency';
  $http_code_data[500] = 'Internal Server Error';
  $http_code_data[501] = 'Not Implemented';
  $http_code_data[502] = 'Bad Gateway';
  $http_code_data[503] = 'Service Unavailable';
  $http_code_data[504] = 'Gateway Timeout';
  $http_code_data[505] = 'HTTP Version Not Supported';
  $http_code_data[507] = 'Insufficient Storage';

  if (isset($http_code_data[$http_code])) {
    header("HTTP/1.0 {$http_code} {$http_code_data[$http_code]}");
    header("Status: {$http_code} {$http_code_data[$http_code]}");
  }
}

function randString($length) {
  $mt_string = 'P5kQjRi6ShTgU7fVeK3pLoM4nNmOlW8dXcY9bZaAzBy0CxDwEv1FuGtHs2IrJq';
  $str = '';
  while ($length--) {
    $str .= $mt_string[mt_rand(0, 61)];
  }
  return $str;
}

function atoi($i) {
  if (!$i) {
    return 0;
  }

  return $i;
}

function alert($msg) {
  echo "<script>alert('{$msg}');</script>";
}

function redirectJs($msg, $url) {
  echo "<script>alert('{$msg}');window.location.href='" . $url . "'</script>";
  exit;
}

function backJs($msg) {
  echo "<script>alert('{$msg}');history.back();</script>";
  exit;
}

/*
 * 获取处理类
 */

function getHandleClass($game_id) {
  $classes = Kerisy::config('System')->get()->classRoute;
  if (!isset($classes[$game_id])) {
    errorReport('不存在该游戏的处理方法', '10001');
  }
  return $classes[$game_id];
}

function url_base64_encode($str) {
  $code = base64_encode($str); //$code='dHQ=';
  $code = str_replace('+', "!", $code); //把所用"+"替换成"!"
  $code = str_replace('/', "*", $code); //把所用"/"替换成"*"
  $code = str_replace('=', "", $code); //把所用"="删除掉
  return $code; //$code='dHQ!'
}

/**
 * 增加加密字符 url编码型不适用,需要先urldecode
 */
function genVerify($data, $signKey) {
  ksort($data);
  $items = array();
  foreach ($data as $key => $value) {
    $items[] = $key . "=" . $value;
  }
  return md5(implode("&", $items) . $signKey);
}

/*
 * 通过http_build_query创建加密字符串 url编码型适用
 */

function makeVerify($data, $signKey) {
  ksort($data);
  return md5(http_build_query($data) . $signKey);
}

/**
 * 增加加密字符(备用)
 */
function createUrl($data) {
  ksort($data);
  $items = array();
  foreach ($data as $key => $value) {
    $items[] = $key . "=" . $value;
  }
  return implode("&", $items);
}

/**
 *  中英文字符串截取 
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true) {
  if (function_exists("mb_substr"))
    return mb_substr($str, $start, $length, $charset);
  elseif (function_exists('iconv_substr')) {
    return iconv_substr($str, $start, $length, $charset);
  }
  $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
  $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
  $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
  $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
  preg_match_all($re[$charset], $str, $match);
  $slice = join("", array_slice($match[0], $start, $length));
  if ($suffix)
    return $slice . "…";
  return $slice;
}
