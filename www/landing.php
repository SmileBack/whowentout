<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>WhoWentOut</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="/landing.css?version=<?= time() ?>" rel="stylesheet" type="text/css"/>


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
    <script type="text/javascript" src="/assets/js/whowentout.component.js?version=2"></script>
    <script type="text/javascript" src="/assets/js/whowentout.queue.js?version=2"></script>
    <script type="text/javascript" src="/assets/js/widgets/jquery.countdowntimer.js?version=2"></script>
    <script type="text/javascript" src="landing.js?version=11"></script>

    <meta name="google-site-verification" content="Qx0f2RFdL3wf2NWU3kcxXacFs020qQ5quH9ZCsVnFlM" />
    <script type="text/javascript">

        var _gaq = _gaq || [];
        _gaq.push(['_setAccount', 'UA-26468050-1']);
        _gaq.push(['_trackPageview']);

        (function() {
            var ga = document.createElement('script');
            ga.type = 'text/javascript';
            ga.async = true;
            ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(ga, s);
        })();

    </script>
</head>

<body>

    <div id="landing">
        <img src="/landing.png?version=5"/>
    </div>

    <div id="countdown_wrapper">
        <div id="countdown" class="time_counter"
             data-target="<?= getenv('countdown_target') ? strtotime(getenv('countdown_target'))
                     : strtotime('October 23, 2011') ?>">
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
    </div>

    <div id="coming_soon">
        <img src="/coming_soon.png?version=1"/>
    </div>

</body>
</html>