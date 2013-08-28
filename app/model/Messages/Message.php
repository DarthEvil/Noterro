<?php

namespace Model\Messages;


class Message {

    protected $_date    = 0;
    protected $_message = "";
    protected $_tags    = array();

    public function __construct($date, $message, $tags)
    {
        $this->_date    = $date;
        $this->_message = $message;
        $this->_tags    = $tags;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return date("d.m.Y h:i:s", $this->_date) ;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->_tags;
    }

    public function toArray()
    {
        return array(
            'date'      => $this->_date,
            'message'   => $this->_message,
            'tags'      => $this->_tags
        );
    }
}