<div class="event_picker tab_panel">
    <h1>What are YOU doing <?= format::night_of($date) ?>?</h1>

    <ul class="tabs">
        <li><a href="#bar_club">Bar/Club</a></li>
        <li><a href="#house_party">House Party</a></li>
        <li><a href="#other">Other</a></li>
    </ul>

    <div class="bar_club pane">
        <?= r::event_list(array(
            'date' => $date,
            'selected_event' => $selected_event,
            'user' => $user,
            'type' => array('bar', 'club')
        )); ?>
    </div>

    <div class="house_party pane">
        <?= r::event_list(array(
            'date' => $date,
            'selected_event' => $selected_event,
            'user' => $user,
            'type' => 'house party'
        )); ?>
    </div>

    <div class="other pane">
        <?= r::event_list(array(
            'date' => $date,
            'selected_event' => $selected_event,
            'user' => $user,
            'type' => 'other'
        )); ?>
    </div>

</div>