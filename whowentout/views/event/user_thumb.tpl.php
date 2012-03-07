<?php
$type = browser::is_mobile() ? 'square' : 'normal';
$image_url = 'http://graph.facebook.com/' . $user->facebook_id . '/picture?type=' . $type;
?>
<div class="user_thumb">
    <?= html_element('img', array('src' => $image_url, 'alt' => $user->first_name)) ?>
</div>
