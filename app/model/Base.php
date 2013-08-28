<?php
/**
 * Created by JetBrains PhpStorm.
 * User: darthevil
 * Date: 29.07.13
 * Time: 21:55
 * To change this template use File | Settings | File Templates.
 */

namespace Model;


class Base {

    protected $app;
    protected $db = null;


    function __construct($app)
    {
        $this->app = $app;

        $server = 'mongodb://'.$this->app['database_config']['server'].':'.$this->app['database_config']['port'];
        $database = $this->app['database_config']['database'];

        $m = new \MongoClient($server);
        $this->db = $m->selectDB($database);

    }

}