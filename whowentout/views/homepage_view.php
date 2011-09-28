<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>WhoWentOut</title>
    <meta name="description" content
    <meta name="author" content="">

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <?= less_asset('reset') ?>
    <?= less_asset('homepage') ?>
    
    <?= js_asset('lib/less.js') ?>

    <?= js_asset('lib/jquery.js') ?>
    <?= js_asset('pages/home.js') ?>
</head>

<body id="<?= body_id() ?>">

<div id="homepage_message">
    <img src="/assets/images/homepage.png?version=3" />
    <a id="enter_button" href="/login">Enter</a>
</div>

</body>
</html>