<?php
$ci =& get_instance();
$featured_message = $ci->option->get('featured_message');
?>
<?php if ($featured_message): ?>
<div class="dashboard_message notice_style">
    <?= $featured_message ?>
</div>
<?php endif; ?>