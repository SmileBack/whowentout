<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="description" content=""/>
    <meta name="author" content="">

    <title><?=isset($title) ? $title : 'WhoWentOut Jobs' ?></title>


    <script type="text/javascript">
        window.settings = <?= json_encode(f()->window_settings) ?>;
    </script>

    <script src="http://js.pusherapp.com/1.9/pusher.js" type="text/javascript"></script>
    <?php $ci =& get_instance();
    $ci->asset->load(array(
                          'job_proxy.js',
                     ));
    ?>

    <?= $ci->asset->js() ?>

</head>

<body id="<?= body_id() ?>">

</body>
</html>