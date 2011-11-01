<?php
 $yesterday = college()->get_time()->getDay(-1);
$checkin_engine = new CheckinEngine();
?>

<div class="parties_attended">

    <div class="checkin_box"></div>

    <?= r('checkin_form', array(
                            'user' => $user,
                          )) ?>

    <?php foreach ($checkin_engine->get_recently_attended_parties_for_user(current_user()) as $party): ?>

    <?=
    r('party_summary', array(
                            'user' => $user,
                            'party' => $party,
                            'smile_engine' => $smile_engine,
                       ))
    ?>
    <?php endforeach; ?>

</div>
