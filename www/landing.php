<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>WhoWentOut</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="/landing.css?version=6" rel="stylesheet" type="text/css"/>


    <?php if (getenv('countdown_target')): ?>
    <script type="text/javascript">
        window.countdown_target = <?= '"' . getenv('countdown_target') . '"' ?>;
    </script>
    <?php endif; ?>

    <script type="text/javascript" src="/assets/js/lib/jquery.js"></script>
    <script type="text/javascript" src="/assets/js/lib/jquery.entwine.js"></script>
    <script type="text/javascript" src="/assets/js/lib/underscore.js"></script>
    <script type="text/javascript" src="/assets/js/lib/timeinterval.js"></script>

    <script type="text/javascript" src="/assets/js/lib/jquery.class.js"></script>
    <script type="text/javascript" src="/assets/js/whowentout.queue.js?version=2"></script>
    <script type="text/javascript" src="landing.js?version=9"></script>
</head>

<body>

<img id="landing" src="/landing.png?version=3"/>

<div id="countdown" class="time_counter">
    <div class="wrap">
        <div class="days counter" data-length="2"></div>
        <h3>days</h3>
    </div>

    <div class="wrap">
        <div class="hours counter" data-length="2"></div>
        <h3>hours</h3>
    </div>

    <div class="wrap">
        <div class="minutes counter" data-length="2"></div>
        <h3>minutes</h3>
    </div>

    <div class="wrap">
        <div class="seconds counter" data-length="2"></div>
        <h3>seconds</h3>
    </div>
</div>

</body>
</html>