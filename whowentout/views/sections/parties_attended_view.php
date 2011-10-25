<?php
 $yesterday = college()->get_time()->getDay(-1);
 $checkin_engine = new CheckinEngine();
?>

<div class="parties_attended">

    <div class="checkin_box"></div>

    <?= load_view('forms/checkin_form') ?>

    <?php foreach ($checkin_engine->get_recently_attended_parties_for_user( current_user() ) as $party): ?>

    <?=
    load_view('party_summary_view', array(
                                         'user' => $user,
                                         'party' => $party,
                                         'smile_engine' => $smile_engine,
                                    ))
    ; ?>
    <?php endforeach; ?>

</div>
