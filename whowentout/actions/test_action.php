<?php

class TestAction extends Action
{
    function execute()
    {
        benchmark::start('woo');
        sleep(1);
        benchmark::end('woo');

        benchmark::start('foo');
        sleep(2);
        benchmark::end('foo');


        krumo::dump(benchmark::summary());
    }
}
