<?php
$class = isset($class) ? $class : '';
$title = isset($title) ? $title : 'view deal';
?>

<?php if ($event && $event->deal): ?>
    <?= a("events/$event->id/deal", $title, array('class' => "action show_deal_link $class")) ?>
<?php endif; ?>