<?php

/**
 * 系统配置文件
 */
/* 数据库配置 */
$CONFIG['system']['db'] = array(
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_password' => '',
    'db_database' => 'ahcms',
    'db_table_prefix' => 'net_',
    'db_charset' => 'utf8',
    'db_conn' => 'false', //数据库连接标识; ture 为长久链接，false 为即时链接
);
/* 默认URL */
$CONFIG['system']['static_url'] = "http://localhost/ahphp/static";

/* 自定义类库配置 */
$CONFIG['system']['lib'] = array(
    'prefix' => 'my'   //自定义类库的文件前缀
);

$CONFIG['system']['route'] = array(
    'defalut_group' => 'new', //系统分组
    'default_controller' => 'index', //系统默认控制器
    'default_action' => 'index', //系统默认方法
    'url_type' => 3 /* 定义URL的形式 1 为普通模式    index.php?c=controller&a=action&id=2
         *              2 为PATHINFO   index.php/controller/action?id=2            
         */
);
/* 自动加载的类 */
$CONFIG['system']['autolib'] = array(
    'router' => SYS_LIB_PATH . '/lib_router.php',
//    'mysql' => SYS_LIB_PATH . '/lib_mysql.php',
//    'template' => SYS_LIB_PATH . '/lib_template.php',
);
/* 缓存配置 */
$CONFIG['system']['cache'] = array(
    'cache_dir' => 'cache', //缓存路径，相对于根目录
    'cache_prefix' => 'cache_', //缓存文件名前缀
    'cache_time' => 1800, //缓存时间默认1800秒
    'cache_mode' => 2, //mode 1 为serialize ，model 2为保存为可执行文件    
);






