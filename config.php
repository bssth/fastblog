<?php
    if(!defined('GOOD_BOY')) // исключаем прямой доступ
       return;

    // Компоненты Composer
    require_once('vendor/autoload.php');

    ini_set('display_errors', false);
    error_reporting(0);

    define('_DB_CONNECTION', 'mongodb://127.0.0.1');
    define('_DB_BASE', 'mcblog');
    /*define('_REDIS_URL', 'redis.cache.windows.net');
    define('_REDIS_PORT', 6380);
    define('_REDIS_PASS', ';*/

    // если используется CloudFlare - записываем реальный IP-адрес, чтобы не путать с прокси
    if(isset($_SERVER["HTTP_CF_CONNECTING_IP"]))
        $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];