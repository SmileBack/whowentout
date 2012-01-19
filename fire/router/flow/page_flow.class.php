<?php

abstract class PageFlow
{
    const START = 'start';
    const END = 'end';

    protected $current_state = PageFlow::START;

    abstract function current();

    public function set_state($state)
    {
        $this->current_state = $state;
    }

    abstract function get_next();

    public function move_next()
    {
        $next = $this->get_next();
        $this->current_state = $next;

        $execute_method = 'execute_' . $this->current_state;
        if (method_exists($this, $execute_method))
            $this->$execute_method();
    }

    public static function transition()
    {
        if (!isset($_SESSION['flow']))
            return;

        /* @var $flow PageFlow */
        $flow = $_SESSION['flow'];

        if ($flow->get_next() == null) {
            unset($_SESSION['flow']);
        }
        else {
            $flow = $_SESSION['flow'];
            $flow->move_next();
        }
    }

    /**
     * @static
     * @param PageFlow $flow
     * @return PageFlow
     */
    public static function start(PageFlow $flow)
    {
        $_SESSION['flow'] = $flow;
        return $flow;
    }

}

