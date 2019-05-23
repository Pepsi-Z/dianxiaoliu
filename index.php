<?php

/* 当前程序版本 */
define('PIN_VERSION', '3.0');
/* 当前程序Release */
define('PIN_RELEASE', '20140124');
/* 应用名称*/
define('APP_NAME', 'app');
/* 应用目录*/
define('APP_PATH', './app/');
/* 模板目录*/
define('TMPL_PATH', './Tpl/');
/* 数据目录*/
define('PIN_DATA_PATH', './data/');
/* 扩展目录*/
define('EXTEND_PATH', APP_PATH . 'Extend/');
/* 配置文件目录*/
define('CONF_PATH', PIN_DATA_PATH . 'config/');
/* 数据目录*/
define('RUNTIME_PATH', PIN_DATA_PATH . 'runtime/');
/* HTML静态文件目录*/
define('HTML_PATH', PIN_DATA_PATH . 'html/');

define('HTML_CACHE_ON',true);// 开启静态缓存'

define('XF_HTTP', 'http://www.xiaoliuge.com/');
define('APPID_INDEX', 'wxdd4810aa0790b0dd');
define('SECRET_INDEX', '20533e302cef32c7ea1a0669edebc69f');
/* DEBUG开关*/
define('APP_DEBUG', true);

require("./_core/setup.php");