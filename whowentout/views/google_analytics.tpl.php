<?php
if (auth()->logged_in()) {
    $user = auth()->current_user();
    $levels = array('visitor' => 1, 'session' => 2, 'page' => 3);
    $commands = array(
        array('_setCustomVar', 1, 'facebook_id', $user->facebook_id, $levels['visitor']),
        array('_setCustomVar', 2, 'gender', $user->gender, $levels['visitor']),
    );
}
else {
    $commands = array();
}
?>

<script type="text/javascript">
  var commands = <?= json_encode($commands) ?>;

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-26468050-1']);

  for (var i = 0; i < commands.length; i++) {
      _gaq.push(commands[i]);
  }

  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>