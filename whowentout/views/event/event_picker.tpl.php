<div class="event_picker tab_panel">
    <h1>What are YOU doing <?= format::night_of($date) ?>?</h1>

    <?php if (!$selected_event): ?>
    <h3>Select a category below. You can change your selection at any time.</h3>
    <?php endif; ?>

    <div class="pre_event_selection">
        <ul class="tabs">
            <li>
                <a href="#bar_club" class="selected">
                    Bar/Club
                </a>
            </li>
            <li>
                <a href="#house_party">
                    House Party
                </a>
            </li>
            <li>
                <a href="#other">
                    Other
                </a>
            </li>
            <li>
                <a href="#undecided">
                    Not Sure Yet
                </a>
            </li>
        </ul>

        <div class="bar_club pane">
            <?= r::event_list(array(
                'date' => $date,
                'selected_event' => $selected_event,
                'user' => $user,
                'type' => array('bar', 'club'),
                'notice' => 'You can switch your selection at any time.',
            )); ?>
        </div>

        <div class="house_party pane">
            <?= r::event_list(array(
                'date' => $date,
                'selected_event' => $selected_event,
                'user' => $user,
                'type' => 'house party',
                'notice' => 'You can switch your selection at any time.'
            )); ?>
        </div>

        <div class="other pane">
            <?= r::event_list(array(
                'date' => $date,
                'selected_event' => $selected_event,
                'user' => $user,
                'type' => 'other',
                'notice' => 'You can switch your selection at any time.'
            )); ?>
        </div>

        <?= r::undecided_pane(array('date' => $date)) ?>

    </div>

    <?php if ($selected_event): ?>
    <div class="event_selection">
        <?= r::profile_small(array('user' => $user)) ?>
        <div class="event_selection_summary">
            <h3>Going to</h3>
            <div class="going_to"><?= $selected_event->name ?></div>
            <a class="switch" href="#switch">Switch</a>
        </div>
    </div>
    <?php endif; ?>

</div>