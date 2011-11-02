<?php
$start_day = college()->get_time()->getDay(0);

if (!$start_day->isPartyDay())
    $start_day = $start_day->getPartyDay(1);

?>

<ul class="parties_attended">
    <?php for ($k = 0; $k >= -5; $k--): ?>
    <li>
        <?php $party_group = new PartyGroup(college()->get_clock(), $start_day->getPartyDay($k)); ?>
        <?= r('party_group', array(
                               'party_group' => $party_group,
                               'user' => current_user(),
                             )) ?>
    </li>
    <?php endfor; ?>
</ul>
