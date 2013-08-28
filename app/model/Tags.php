<?php
/**
 * Created by JetBrains PhpStorm.
 * User: darthevil
 * Date: 14.08.13
 * Time: 19:35
 * To change this template use File | Settings | File Templates.
 */

namespace Model;
require_once 'Base.php';
require_once 'Tags/Tag.php';

class Tags extends Base {

    public function insert ($tag)
    {
        $collection = new \MongoCollection($this->db, $this->app['database_config']['collections']['tags']);

        $criteria   = array(
            'tag' => $tag,
        );
        $new_object = array(
            '$inc'  => array(
                'count' => 1,
            ),
        );
        $options    = array(
            'upsert' => true
        );

        $collection->update(
            $criteria,
            $new_object,
            $options
        );
    }

    public function getList()
    {
        $result = array();

        $collection = new \MongoCollection($this->db, $this->app['database_config']['collections']['tags']);
        $sort = array(
            'count' => 1,
            'tag'   => 1,
        );

        $cursor = $collection->find()->sort($sort);
        foreach ($cursor as $row) {
            $message = new \Model\Tags\Tag($row['tag'], $row['count']);
            $result[] = $message;
        }
        return $result;
    }

}