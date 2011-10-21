<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content ="" />
    <meta name="author" content="">
    <meta name="google-site-verification" content="Qx0f2RFdL3wf2NWU3kcxXacFs020qQ5quH9ZCsVnFlM" />

    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <?= css_asset('jquery.autocomplete') ?>
    <?= css_asset('jquery.jcrop') ?>

    <?= less_asset('style') ?>
    
    <!--[if IE]>
    <?= less_asset('ie') ?>
    <![endif]-->
    
    <?= js_asset('lib/less.js') ?>
    <?= js_asset('lib/modernizr.js') ?>

    <?= js_asset('asset.js') ?>
    
    <?= load_view('development/google_analytics_view') ?>
</head>

<body id="<?= body_id() ?>">

<?= load_view('js/wwo_view') ?>

<div id="notice"></div>

<div id="current_time" class="current_time">
    <?= current_time(TRUE)->format('D, M j g:i a') ?>
</div>

<div id="background">
    <!--<img src="/assets/images/background_club.png" />-->
</div>

<div id="page">

    <header class="main">

        <div id="logo">

            <a href="/">
                <img src="/assets/images/logo.png?version=9"/>
            </a>

            <?php if (FALSE): ?>
            (
            <?= current_time(TRUE)->format('Y-m-d H:i:s') ?>,
            <?= current_user()->first_name ?>,
            <?= fb()->getUser() ?>
            )
            <?php endif; ?>

        </div>

        <nav>

            <?php if (logged_in()): ?>
            <?= anchor('dashboard', 'My Parties', array('class' => 'dashboard_link')) ?>
            <?= anchor('friends', 'My Friends', array('class' => 'friends_link')) ?>
            <?php endif; ?>

            <?php if (logged_in() && current_user()->is_admin()): ?>
            <?= anchor('admin', 'Admin', array('class' => 'admin_link')) ?>
            <?php endif; ?>

            <?php if (logged_in()): ?>
            <?= anchor('logout', 'Logout') ?>
            <?php else: ?>
            <?= anchor('login', 'Login') ?>
            <?php endif; ?>

        </nav>

        <div class="clearboth" style="clear: both;"/>
    </header>

    <?php if (get_message()): ?>
    <div class="message">
        <?= pull_message() ?>
    </div>
    <?php endif; ?>

    <div id="page_content">

        <?= $page_content ?>

    </div>
    <!-- page_content end -->

    <?php if (logged_in()): ?>
    <div id="sidebar">
        <div class="my_info_view">
            <?= load_view('sections/my_info_view') ?>
        </div>
        <ul id="notifications"></ul>
    </div>
    <?php endif; ?>

</div>
<!-- page end -->

<?= load_view('js_view') ?>

<?= load_view('development/benchmarks_view.php') ?>


</body>
</html>