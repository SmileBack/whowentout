<!doctype html>
<!--[if lt IE 7 ]> <html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="en" class="no-js">
  <!--<![endif]-->
  <head>
    
    <meta charset="utf-8">
    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <?= css_asset('reset') ?>
    <?= css_asset('jquery.autocomplete') ?>
    <?= css_asset('jquery.jcrop') ?>
    
    <?= less_asset('style') ?>
    <?= js_asset('less.js') ?>
    
    <?= js_asset('modernizr.js') ?>
    
    <!-- Load the AJAX API -->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    
  </head>

  <body id="<?= body_id() ?>">	
    
    <?= load_view('wwo_view') ?>
    
    <div id="current_time" class="current_time">
      <?= current_time(TRUE)->format('D, M j g:i a') ?>
    </div>
    
    <div id="container">
      
      <header class="main">

        <div id="logo">
          <a href="/">
            <img src="/assets/images/logo.png" />
          </a>
          <?php if (FALSE): ?>
          (
            <?= current_time(TRUE)->format('Y-m-d H:i:s'); ?>,
            <?= current_user()->first_name ?>,
            <?= fb()->getUser(); ?>
          )
          <?php endif; ?>
        </div>

        <nav>
          
          <?php if (logged_in()): ?>
          <?= anchor('dashboard', 'My Dashboard'); ?>
          <?php endif; ?>
          
          <?php if (logged_in() && current_user()->is_admin()): ?>
          <?= anchor('admin', 'Admin') ?>
          <?php endif; ?>
          
          <?php if (logged_in()): ?>
            <?= anchor('logout', 'Logout') ?>
          <?php else: ?>
            <?= anchor('login', 'Login') ?>
          <?php endif; ?>
          
        </nav>
        
      </header>
      
      <?php if (get_message()): ?>
        <section class="message">
          <?= pull_message() ?>
        </section>
      <?php endif; ?>
