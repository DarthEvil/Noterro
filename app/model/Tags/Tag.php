<?php
namespace Model\Tags;


class Tag {

    protected $_tag     = false;
    protected $_count   = 0;

    public function getCount()
    {
        return $this->_count;
    }

    public function getTag()
    {
        return $this->_tag;
    }

    function __construct($tag, $count)
    {
        $this->_tag     = $tag;
        $this->_count   = $count;
    }


}