<?php
$classes = array('event_option', 'new_event');
$base_types = is_array($base_type) ? $base_type : array($base_type);

foreach ($base_types as &$type) {
    $type = "$type base";
    $classes[] = $type;
}

?>
<form method="post" action="/checkin" class="<?= implode(' ', $classes) ?>">

    <?= form::hidden('event_id', 'new') ?>

    <div class="place">
        <?= form::hidden('event[date]', $date->format('Y-m-d')) ?>

        <?=
        form::input('event[name]', '', array(
            'class' => 'inline_label',
            'autocomplete' => 'off',
            'title' => 'Add your own',
        ))
        ?>

        <?= app()->places_dropdown('event[place_id]', $base_types) ?>

    </div>

    <div class="badge">
        <input type="submit" name="op" class="add_event" value="add" />
    </div>

</form>
