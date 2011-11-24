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
    <a class="events active" href="/events/view/admin">Events</a>
    <a class="events" href="/events">My Parties</a>
    <a class="logout">Logout</a>
</nav>

<div id="page">

    <div id="content">
        <?= $content ?>
    </div>

</div>
<!-- page end -->

</body>
</html>
