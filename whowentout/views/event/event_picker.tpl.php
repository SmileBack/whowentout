<div class="event_picker tab_panel">
    <h1>What are YOU doing <?= format::night_of($date) ?>?</h1>

    <?php if (!$selected_event): ?>
    <h3>Select a category below. You can change your selection at any time.</h3>
    <?php endif; ?>

    <div class="pre_event_selection <?= $selected_event ? 'hidden' : '' ?>">
       
			<ul class="tabs">
	            <li>
	                <a href="#bar_club" class="selected">
	                    Bar/Club	
	                </a>
					<div class="arrow"></div>
	            </li>
	            <li>
	                <a href="#house_party">
	                    Parties
	                </a>
					<div class="arrow"></div>
	            </li>
	            <li>
	                <a href="#other">
	                    Other
	                </a>
					<div class="arrow"></div>
	            </li>
	            <li>
	                <a href="#undecided">
	                    Not Sure Yet
	                </a>
					<div class="arrow"></div>
	            </li>
	        </ul>
		
        <div class="bar_club pane">
            <?= r::event_list(array(
                'date' => $date,
                'selected_event' => $selected_event,
                'user' => $user,
                'type' => array('bar', 'club'),
                'explanation' => '<p><em>Bar/Club</em>: <strong>Select the bar/club you are thinking of going to. You can change your selection at any time.</strong></p>',
            )); ?>
        </div>

        <div class="house_party pane">
            <?= r::event_list(array(
                'date' => $date,
                'selected_event' => $selected_event,
                'user' => $user,
                'type' => 'house party',
                'explanation' => 'Add a party below or select one that you are going to.',
            )); ?>
        </div>

        <div class="other pane">
            <?= r::event_list(array(
                'date' => $date,
                'selected_event' => $selected_event,
                'user' => $user,
                'type' => 'other',
                'explanation' => 'Doing something more chill tonight? Add it to the list below.',
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

            <ul>
                <li><a class="switch" href="#switch">switch event</a></li>
                <li><?= r::show_deal_link(array('event' => $selected_event)) ?></li>

                <?php if ($selected_event->place->type != 'undecided base'): ?>
                <li><?= r::event_invite_link(array('event' => $selected_event)) ?></li>
                <?php endif; ?>

            </ul>

        </div>
    </div>
    <?php endif; ?>

</div>