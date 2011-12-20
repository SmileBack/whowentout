<?php
$image_url = 'http://graph.facebook.com/' . $user->facebook_id . '/picture?type=normal';
?>
<div class="user_thumb">
    <?= html_element('img', array('src' => $image_url, 'alt' => $user->first_name)) ?>
</div>
