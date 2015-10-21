<?php
/**
 * Created by PhpStorm.
 * User: Lin Wang
 * Date: 2015/8/27
 * Time: 17:04
 * Desc: 系统配置文件
 */

return new \Phalcon\Config(array(
    //master 主数据库配置
    'dbMaster' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'bus',
        'charset'     => 'utf8',
    ),
    //slave 从数据库配置
    'dbSlave1' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'bus',
        'charset'     => 'utf8',
    ),
    'dbSlave2' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => 'root',
        'dbname'      => 'bus',
        'charset'     => 'utf8',
    ),
    //缓存生存时间
    'cache_life' => array(
        'lifetime'    => 86400
    ),
    //redis缓存配置
    'redis' => array(
        'host'        => '127.0.0.1',
        'port'        => 6379
    ),
    //Beanstalk消息队列配置
    'beanstalk' => array(
        'host' => '192.168.0.21',
        'port' => '11300'
    ),

    //tokenlife token缓存时间
    'tokenlife'     =>  86400,
    //token混淆码
    'tokenconfuse'  =>  '*7&^()#@*!!',

    // sms短信配置
    'sms'			=>	array(
        'smsurl'	=>	'http://sichuan.ums86.com:8899/sms/Api/Send.do?',
        'smsreply'	=>	'http://sichuan.ums86.com:8899/sms/Api/reply.do?',
        'smsspcode'	=>	'103909',
        'smsuser'	=>	'sc_fyzkj',
        'smspass'	=>	'fyzums888',
    ),
    // 注册sms通知模板
    'register_tpl'	=>	'您的验证码是######，在5分钟内有效。如非本人操作请忽略本短信。【智出行】',
    'login_tpl'	    =>	'您的验证码是######，在5分钟内有效。如非本人操作请忽略本短信。【智出行】',

));