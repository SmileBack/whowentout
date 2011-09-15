<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>
    <meta name="description" content
    <meta name="author" content="">

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <?= css_asset('jquery.autocomplete') ?>
    <?= css_asset('jquery.jcrop') ?>

    <?= less_asset('style') ?>

    <?= js_asset('lib/less.js') ?>
    <?= js_asset('lib/modernizr.js') ?>

</head>

<body id="<?= body_id() ?>">

<?= load_view('js/wwo_view') ?>
<?= load_view('js/current_user_view') ?>

<div id="notice"></div>

<?php if (logged_in()): ?>
<div id="chatbar"></div>
    <?php endif; ?>

<div id="current_time" class="current_time">
    <?= current_time(TRUE)->format('D, M j g:i a') ?>
</div>

<div id="page">

    <header class="main">

        <div id="logo">
            <a href="/">
                <img src="/assets/images/logo_small.png"/>
            </a>
            <?php if (FALSE): ?>
            (
            <?= current_time(TRUE)->format('Y-m-d H:i:s')
            ; ?>,
            <?= current_user()->first_name ?>,
            <?= fb()->getUser()
            ; ?>
            )
            <?php endif; ?>
        </div>

        <nav>

            <?php if (logged_in()): ?>
            <?= anchor('dashboard', 'My Dashboard')
            ; ?>
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
