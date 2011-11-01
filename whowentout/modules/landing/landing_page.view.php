<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <title>WhoWentOut</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="/assets/css/landing.css?version=<?= time() ?>" rel="stylesheet" type="text/css"/>
    
    <?php $ci =& get_instance(); ?>
    <?= $ci->asset->js(array(
                           'pages/landing.js',
                         )) ?>

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
        <img src="/assets/images/landing/landing.png?version=7"/>
    </div>

    <div id="coming_soon">
        <img src="/assets/images/landing/coming_soon.png?version=6"/>
    </div>

</body>
</html>
