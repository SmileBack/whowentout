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
    
    <?= r('google_analytics') ?>
</head>

<body id="<?= body_id() ?>">

<?= r('wwo') ?>

<div id="notice" class="notice"></div>

<div id="current_time" class="current_time">
    <?= college()->get_time()->format('D, M j g:i a') ?>
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
            <?= anchor('dashboard', 'My Parties' . '<span class="num_checkins"></span>', array('class' => 'dashboard_link')) ?>
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
        <div class="my_info_view user <?= 'user_' . current_user()->id ?>">
            <?= r('my_info') ?>
        </div>
        <ul id="notifications"></ul>
    </div>
    <?php endif; ?>

</div>
<!-- page end -->

<?= r('js') ?>

<?= r('benchmarks') ?>


</body>
</html>