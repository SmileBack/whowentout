<?php $selected_party = $party_group->get_selected_party($user); ?>
<?php $party_group_name = 'party_id_' . $party_group->get_date()->format('Ymd'); ?>
<?php $checkin_engine = new CheckinEngine(); ?>
<?php $phase = $party_group->get_phase(); ?>
<div class="party_group <?= 'party_group_' . $party_group->get_date()->format('Ymd') ?> infobox">

    <h2>
        <?= $party_group->get_date()->format('l, M. jS') ?>
    </h2>

    <div class="party_group_body">

        <div class="party_group_left">
            <ul>
                <?php foreach ($party_group->get_parties() as $party): ?>
                <li>
                    <label>

                        <?php
                        $radio = array(
                        'name' => $party_group_name,
                        'value' => $party->id,
                        'checked' => $selected_party == $party,
                    );

                        if ($phase == PartyGroupPhase::CheckinsClosed) {
                            $radio['disabled'] = 'disabled';
                        }
                        ?>

                        <?= form_radio($radio);?>
                        <?= $party->place->name; ?>
                        <?php if ($selected_party): ?>
                        ( <?= $checkin_engine->get_num_checkins_for_party($party) ?> )
                        <?php else: ?>
                        ( ? )
                        <?php endif; ?>
                    </label>
                </li>
                <?php endforeach; ?>
            </ul>

        </div>

        <div class="party_group_right">
            <?php if ($phase == PartyGroupPhase::EarlyCheckin): ?>

            <?php if ($selected_party): ?>

                <?=
                r('time_counter', array(
                                       'target' => $party_group->get_checkin_phase_start()->getTimestamp(),
                                  ))
                ?>

                <?= r('recent_attendees', array('party' => NULL, 'count' => 4)) ?>

                <?php else: ?>
                <div class="large_message">
                    Select what party you will be going to.
                </div>
                <?php endif; ?>

            <?php elseif ($phase == PartyGroupPhase::Checkin): ?>

            <?php if ($selected_party): ?>
                <?= r('recent_attendees', array('party' => $selected_party, 'count' => 4)) ?>
                <?php else: ?>
                <div class="large_message">
                    Select what party you went to.
                </div>
                <?php endif; ?>

            <?php  elseif ($phase == PartyGroupPhase::CheckinsClosed): ?>

            <?php if ($selected_party): ?>
                <?= r('recent_attendees', array('party' => $selected_party, 'count' => 4)) ?>
                <?php else: ?>
                <div class="large_message">
                    Checkins have closed.
                </div>
                <?php endif; ?>

            <?php endif; ?>

            <div>
                <?= $phase ?>
            </div>
        </div>

    </div>

</div>
