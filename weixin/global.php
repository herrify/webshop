<?php
// 全局
date_default_timezone_set('Asia/Chongqing');
if (!defined('ROOT_PATH')) {
  die("Security violation");
}

define('DB_HOST', '101.200.215.232');
define('DB_PORT', '');
define('DB_NAME', 'guanjie');
define('DB_USERNAME', 'guanjie');
define('DB_PASSWORD', 'guanjiejtdadmin');

//define('ROOT_PATH', dirname(__FILE__)."/");
define('LIB_PATH', ROOT_PATH.'lib/');
define('DB_CONFIG_PATH', LIB_PATH.'db_config/');
define('TP_PATH',LIB_PATH.'template/');

require_once LIB_PATH.'OhbaDatabaseMySql.php';
require_once LIB_PATH.'OhbaTemplate.php';
require_once LIB_PATH.'validator.php';
require_once LIB_PATH.'API.php';

$lang = array();
require_once LIB_PATH.'lang/cn.php';

error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('COOKIE_EXPIRE',86400);

define('SITE_ADMIN_SESSION_PREFIX','TokuteiAdmin9863');
define('SITE_USER_SESSION_PREFIX','TokuteiUser4126');

ob_start();
session_cache_limiter("private, must-revalidate");
session_start();
?>
