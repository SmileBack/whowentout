<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="description" content=""/>
    <meta name="author" content="">

    <title><?=isset($title) ? $title : 'WhoWentOut' ?></title>

    <link rel="shortcut icon" href="/favicon.ico">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <link rel="stylesheet/less" type="text/css" href="/css/reset.<?= time() ?>.less">
    <link rel="stylesheet/less" type="text/css" href="/css/styles.<?= time() ?>.less">

    <script src="/js/less.js" type="text/javascript"></script>

</head>

<body>

<nav id="nav">
    <div class="logo" href="/"><img src="/images/logo.png" /></div>
    <?= a('events', 'Events') ?>
    <?= a('messages', 'Messages (3)') ?>
    <?= auth()->get_login_link() ?>
</nav>

<div id="page">

    <div id="content">
        <?= $content ?>
    </div>

</div>
<!-- page end -->

</body>
</html>
