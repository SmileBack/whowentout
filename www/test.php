<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once '../fire/core/boot.php';
boot();

$action_router = new ActionRouter();
$action_router->add('date/(:any)', 'ViewDayAction/$1');

//$action_router->execute('date/20120119');

$parser = new PHPClassParser();
$classes = $parser->get_file_classes(APPPATH . 'whowentout/controllers/woo.php');
krumo::dump($classes);

//$target = $route_matcher->route('date/20120119');
//krumo::dump($target);
//$action = new ViewDayAction();
//$action->execute('20120119');
?>