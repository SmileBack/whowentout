<?php

class DatabaseLinkPath
{

    /* @var $links DatabaseLink */
    public $links = array();

    function __construct($links = array())
    {
        $this->add_links($links);
    }

    function __clone()
    {
        foreach ($this->links as &$link) {
            $link = clone $link;
        }
    }

    /**
     * @return DatabaseLinkPath
     */
    function reverse()
    {
        $link_path = clone $this;
        $link_path->links = array_reverse($link_path->links);
        /* @var $link DatabaseTableLink */
        foreach ($link_path->links as &$link) {
            $link = $link->reverse();
        }
        return $link_path;
    }
    
    /**
     * @return DatabaseTableLink
     */
    function add_link_path(DatabaseLinkPath $path)
    {
        $sum = clone $this;
        $sum->add_links($path->links);
        return $sum;
    }

    function add_link(DatabaseLink $link)
    {
        $this->links[] = $link;
    }

    function add_links(array $links)
    {
        foreach ($links as $link) {
            $this->add_link($link);
        }
    }

    function __toString()
    {
        $str = array();
        foreach ($this->links as $link) {
            $str[] = strval($link);
        }
        return implode(', ', $str);
    }

}
