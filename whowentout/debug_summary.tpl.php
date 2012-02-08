<?=
r::debug_panel(array(
    'tabs' => array(
        'main' => r::table(array(
            'rows' => benchmark::summary('main'),
        )),
        'database' => r::table(array(
            'rows' => benchmark::summary('database'),
        ))
    ),
))
?>
