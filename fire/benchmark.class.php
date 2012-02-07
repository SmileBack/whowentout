<?php

class benchmark
{

    public static $blocks = array();
    public static $start_times = array();

    public static function  start($marker)
    {
        if (isset(static::$start_times[$marker]))
            throw new Exception("Already started $marker.");

        static::$start_times[$marker] = microtime(true);
    }

    public static function end($marker)
    {
        if (!isset(static::$start_times[$marker]))
                    throw new Exception("Never started $marker.");

        $block = array(
            'marker' => $marker,
            'start' => static::$start_times[$marker],
            'end' => microtime(true),
        );
        $block['elapsed'] = $block['end'] - $block['start'];

        unset(static::$start_times[$marker]);

        static::$blocks[$marker][] = $block;
    }

    public static function elapsed($marker)
    {
        if (!isset(static::$blocks[$marker]))
            return 0;

        $elasped = 0;
        foreach (static::$blocks[$marker] as $block)
            $elasped += $block['elapsed'];

        return $elasped;
    }

    public static function summary()
    {
        $summary = array();
        foreach (static::$blocks as $marker => $blocks) {
            $summary[$marker] = static::elapsed($marker);
            $summary[$marker] = round($summary[$marker], 2);
        }
        arsort($summary);
        return $summary;
    }

}
