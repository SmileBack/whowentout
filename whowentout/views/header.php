<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html lang="en" class="no-js"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="shortcut icon" href="/favicon.ico">
	<link rel="apple-touch-icon" href="/apple-touch-icon.png">
	
	<?= css_asset('reset') ?>
	<?= css_asset('style') ?>
	
	<?= js_asset('jquery.js') ?>
	<?= js_asset('modernizer.js') ?>
</head>

<body>	
	<div id="container">
		
		<header class="main">
			<div id="logo">WhoWentOut</div>
			
			<ul id="menu">
			  <li><?= anchor('dashboard', 'My Dashboard'); ?></li>
			  <li><?= anchor('homepage', 'Logout'); ?></li>  
			</ul>
		</header>