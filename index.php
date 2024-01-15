<?php
    /**
     * Ты же хороший мальчик?
     */
    define('GOOD_BOY', true);

    /**
     * В качестве автозагрузчика и менеджера расширений используется Composer, это его автозагрузчик
     * @see composer.json
     */
    require_once('vendor/autoload.php');

    /**
     * Инклудим конфигурационный файл, где проходит первичное объявление нужных переменных
     */
    try {
        require_once('config.php');
    } catch(Exception $e) {
        header("HTTP/1.1 500 Internal Server Error");
        http_response_code(500);
        return;
    }

    use Altmetric\MongoSessionHandler;
    use FastBlog\DB;
    if(isset($_COOKIE['access'])) {
        $sessions = DB::i()->select('sessions');
        $handler = new MongoSessionHandler($sessions);
        session_set_save_handler($handler);
        session_start();

        if(isset($_SESSION['debug'])) {
            ini_set('display_errors', true);
            error_reporting(E_ALL);
        }
    }

    ob_start();
    header('Access-Control-Allow-Origin: https://'.$_SERVER['SERVER_NAME']);
    header('X-Frame-Options: DENY');

    $route = $_SERVER['REQUEST_URI'];

    if (strpos($route, '?') !== false)
        $route = strstr($route, '?', true);
    if (strpos($route, '/') === 0)
        $route = substr($route, 1);
    if (!strlen($route))
        $route = 'index';

    if($route === 'favicon.ico') {
        header('Location: /icon/favicon.ico');
        return;
    }

    if(strpos($route, '.html') !== false) {
        $_use_post_domain = $route;
        $route = 'post';
    }

    if(strpos($route, 'cat') === 0) {
        $from = strpos($route, 'cat/') + strlen('cat/');
        $filter_cat = substr($route, $from);
        $filter_cat = explode('-', $filter_cat);
        $filter_cat = $filter_cat[0];

        if(strpos($route, '/page') !== false) {
            $from = strpos($route, '/page') + strlen('/page');
            $set_current_page = substr($route, $from);
        }

        $route = 'posts';
    }

    $page = __DIR__ . '/controller/' . $route . '.php';

    try {
        require_once( file_exists($page) ? $page : 'controller/404.php' );
    } catch(\FastBlog\AccessException $e) {
        header('HTTP/1.1 403 Forbidden');
        http_response_code(403);
        print("<h2>403: Ошибка доступа</h2>");
        return;
    } catch(\Exception $e) {
        if($e->getMessage() === '404') {
            require_once( 'controller/404.php' );
            return;
        }

        header("HTTP/1.1 500 Internal Server Error");
        http_response_code(500);
        print("<h2>Возникла внутренняя ошибка сервера</h2>" . $e);
        return;
    }