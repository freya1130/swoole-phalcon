<?php
/**
 * Created by PhpStorm.
 * User: Lin Wang
 * Date: 2015/8/27
 * Time: 16:06
 */
class App{
    public static function run()
    {
        global $di;
        $di = new \Phalcon\DI\FactoryDefault();
        self::initSystemConst();
        self::initSystemService();
        self::initAutoloaders();
        $application = new \Phalcon\Mvc\Application($di);
        //Register the installed modules
        $application->registerModules(array(
            'home' => array(
                'className' => 'Application\Home\Module',
                'path' => '../application/modules/home/module.php'
            ),
            'admin' => array(
                'className' => 'Application\Admin\Module',
                'path' => '../application/modules/admin/module.php'
            ),
            'api' => array(
                'className' => 'Application\Api\Module',
                'path' => '../application/modules/Api/module.php'
            )
        ));
        echo $application->handle()->getContent();
    }

    //初始化系统常量
    public static function initSystemConst()
    {
        define('ROOT_PATH', realpath('../'));
        define('PUBLIC_PATH',ROOT_PATH."/public");
        define('APP_PATH',ROOT_PATH."/application");
        define('COMMON_PATH',APP_PATH."/common");
        define('MODULES_PATH',APP_PATH.'/modules');
        define('CONFIG_PATH',ROOT_PATH."/config");
        define('RUNTIME_PATH',ROOT_PATH . '/runtime');
        define('CACHE_PATH',RUNTIME_PATH."/cache");
        define('LOGS_PATH',RUNTIME_PATH."/logs");
        define('FUNCTIONS_PATH',COMMON_PATH."/function");
        define('LIB_PATH',COMMON_PATH."/library");
        define('PLUGINS_PATH',COMMON_PATH."/plugins");
    }

    //初始化系统服务
    public static function initSystemService()
    {
        global $di;
        //读取配置项
        $config = require(CONFIG_PATH . "/config.php");
        $di->setShared('config', function() use ($config){
            return $config;
        });

        //设置master数据库
        $di->setShared('dbMaster', function() use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql($config->dbMaster->toArray());
        });
        //设置slave1数据库
        $di->setShared('dbSlave1', function() use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql($config->dbSlave1->toArray());
        });
        //设置slave2数据库
        $di->setShared('dbSlave2', function() use ($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql($config->dbSlave2->toArray());
        });

        //设置redis缓存
        $di->setShared('redis', function() use ($config){
            $frontCache = new \Phalcon\Cache\Frontend\Data($config->cache_life->toArray());
            return new Phalcon\Cache\Backend\Redis($frontCache, $config->redis->toArray());
        });

        //设置Beanstalk队列
        $di->setShared('queue', function() use ($config){
            return new Phalcon\Queue\Beanstalk($config->beanstalk->toArray());
        });
		
		//设置session
		$di->setShared('session', function () {
			$session = new \Phalcon\Session\Adapter\Files();
			$session->start();
			return $session;
		});

        //设置router
        $di->set('router', function () {
            $router = new \Phalcon\Mvc\Router();
            $router->setDefaultModule("home");
            $router->add('/:module/:controller/:action', array(
                'module' => 1,
                'controller' => 2,
                'action' => 3,
            ));
            return $router;
        });
    }

    //初始化loader
    public static function initAutoloaders()
    {
        $loader = new \Phalcon\Loader();
        //注册公共命名空间
        $loader->registerNamespaces(array(
            'Application\Common\Controller' 	=> '../application/common/controller/',
            'Application\Common\Model' 		    => '../application/common/model/'
        ))->register();

        //注册公共插件、公共类库,公共函数库
        $loader->registerDirs(array(
            LIB_PATH,
            PLUGINS_PATH,
            FUNCTIONS_PATH
        ))->register();
    }
}
