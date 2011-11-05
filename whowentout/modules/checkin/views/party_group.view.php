<?php $selected_party = $party_group->get_selected_party($user); ?>
<?php $party_group_name = 'party_id_' . $party_group->get_date()->format('Ymd'); ?>
<?php $checkin_engine = new CheckinEngine(); ?>
<?php $phase = $party_group->get_phase(); ?>
<div class="party_group <?= 'party_group_' . $party_group->get_date()->format('Ymd') ?> <?= $phase ?>"
     data-phase="<?= $phase ?>"
     data-selected-party-id="<?= $selected_party ? $selected_party->id : '' ?>">


    <h2>
        <?= $party_group->get_date()->format('l, M. jS') ?>
    </h2>

    <div class="party_group_body">

        <?=
        r('party_group_badge', array(
                                     'party_group' => $party_group,
                                     'user' => $user,
                                   ))
        ?>
        
        <div class="party_group_left">
            
            <h3>
            <?php if ($phase == PartyGroupPhase::EarlyCheckin): ?>
                I'm Attending:
            <?php else: ?>
                I Attended:
            <?php endif; ?>
            </h3>

            <ul>
                <?php foreach ($party_group->get_parties() as $party): ?>
                <li>
                    <label>

                        <?php
                        $radio = array(
                        'name' => $party_group_name,
                        'value' => $party->id,
                        'checked' => $selected_party == $party,
                        'data-party-name' => $party->place->name,
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


            <?php if ($selected_party): ?>

            <div class="selected_party" style="width: 150px;" class="infobox">
                <?= $selected_party->place->name ?>
            </div>

            <?=
            form_open("party/{$selected_party->id}", array('class' => 'go_to_party_gallery'))
            ; ?>
            <input type="submit" value="Go To Party Gallery"/>
            <?= form_close() ?>

            <?php endif; ?>

            <?php if ($phase == PartyGroupPhase::EarlyCheckin): ?>

            <?php if ($selected_party): ?>

                <?=
                r('time_counter', array(
                                       'target' => $party_group->get_checkin_phase_start()->getTimestamp(),
                                  ))
                ?>

                <?= r('recent_attendees', array('party' => NULL, 'count' => 4)) ?>

                <?php else: ?>
                <div class="large_message arrow_left">
                    Select the party you're going to so you can:
                    <ol>
                        <li>See how many people are going to each party</li>
                        <li>Access the party gallery</li>
                    </ol>
                </div>
                <?php endif; ?>

            <?php elseif ($phase == PartyGroupPhase::Checkin): ?>

            <?php if ($selected_party): ?>
                <?= r('recent_attendees', array('party' => $selected_party, 'count' => 4)) ?>
                <?php else: ?>
                <div class="large_message arrow_left">
                    Select the party you went to so you can:
                    <ol>
                        <li>See how many people went to each party</li>
                        <li>Access the party gallery</li>
                    </ol>
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

            <?php if ($selected_party): ?>
            <?=
            r('party_notices', array(
                                    'user' => $user,
                                    'party' => $selected_party,
                                    'smile_engine' => new SmileEngine(),
                               )) ?>
            <?php endif; ?>

        </div>

    </div>

</div>
