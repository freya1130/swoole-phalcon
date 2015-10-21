<?php

/**
 * HttpSerever Swoole 服务器
 * User: Kp
 * Date: 2015/10/21
 * Time: 12:52
 */
define('ROOT_PATH', dirname(__DIR__));
class HttpServer
{
    public static $instance;

    public $http;
    public static $get;
    public static $post;
    public static $header;
    public static $server;
    private $application;

    public function __construct() {
        // 创建swoole_http_server对象
        $http = new swoole_http_server("0.0.0.0", 9501);
        // 设置参数
        $http->set(array(
                'worker_num' => 16,
                'daemonize' => false,
                'max_request' => 10000,
                'dispatch_mode' => 1
            ));
        $http->setGlobal(HTTP_GLOBAL_ALL);
        // 绑定WorkerStart
        $http->on('WorkerStart' , array( $this , 'onWorkerStart'));
        // 绑定request
        $http->on('request', function ($request, $response) {
            ob_start();
            try {
                //注入uri
                $_GET['_url'] = $request->server['request_uri'];
                $application = new \Phalcon\Mvc\Application($this->di);
                echo $application->handle()->getContent();
            } catch (Exception $e) {
                echo $e->getMessage();
            }
            $result = ob_get_contents();
            ob_end_clean();
            $response->end($result);
        });
        $http->start();
    }

    // WorkerStart回调
    public function onWorkerStart() {
        $loader = new \Phalcon\Loader();
        $loader->registerDirs(array(
            ROOT_PATH . '/application/controller/',
            ROOT_PATH . '/application/model/'
        ));
        $loader->register();
        $this->di = new \Phalcon\Di\FactoryDefault();
        //Registering the view component
        $this->di->set('view', function(){
            $view = new \Phalcon\Mvc\View();
            $view->setViewsDir(ROOT_PATH . '/application/views/');
            return $view;
        });
    }

    // 获取实例对象
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}

HttpServer::getInstance();