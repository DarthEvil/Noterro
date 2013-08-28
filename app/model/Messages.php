<?php

namespace Model;
require_once 'Base.php';
require_once 'Messages/Message.php';

class Messages extends Base {

    public function getLastMessageTime()
    {
        $result = 0;

        $collection = new \MongoCollection($this->db, $this->app['database_config']['collections']['messages']);

        $items = $collection->find(array(), array('date'))->sort(array('date' => \MongoCollection::DESCENDING))->limit(1);

        $item = $items->getNext();

        if (!empty($item))
        {
            $result = $item['date'];
        }

        return $result;
    }

    public function Insert(\Model\Messages\Message $message)
    {
        $collection = new \MongoCollection($this->db, $this->app['database_config']['collections']['messages']);
        $result = $collection->insert($message->toArray());
        return $result;
    }

    public function getMessagesList(Array $tags = array(), Array $args = array())
    {
        $result = array();

        $collection = new \MongoCollection($this->db, $this->app['database_config']['collections']['messages']);

        $sort = array(
            'date' => 1,
        );

        $find = array();

        if (!empty($tgs))
        {
            $find['tags'] = array(
                '$all' => $tags,
            );
        }

        $cursor = $collection->find($find)->sort($sort);


        foreach ($cursor as $row) {

            $message = new \Model\Messages\Message($row['date'], $row['message'], $row['tags']);
            $result[] = $message;
        }

        return $result;
    }

}