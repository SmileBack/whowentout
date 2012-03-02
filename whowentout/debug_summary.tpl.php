<?=
r::debug_panel(array(
    'tabs' => array(
        'main' => r::table(array(
            'rows' => benchmark::summary('main'),
        )),
        'database' => r::table(array(
            'rows' => benchmark::summary('database'),
        )),
        'render' => r::table(array(
            'rows' => benchmark::summary('render'),
        )),
        'stats' => r::benchmark_stats(benchmark::stats()),
    ),
))
?>
