<?php

$user = current_user();

$start_day = college()->get_time()->getDay(0);

if (!$start_day->isPartyDay())
    $start_day = $start_day->getPartyDay(1);

$upcoming_party_groups = array();
$past_party_groups = array();

for ($k = 0; $k >= -5; $k--) {
    $party_group = new PartyGroup(college()->get_clock(), $start_day->getPartyDay($k));
    if ($party_group->get_phase() == PartyGroupPhase::EarlyCheckin)
        $upcoming_party_groups[] = $party_group;
    else if ($party_group->get_phase() == PartyGroupPhase::Checkin
             || $party_group->get_selected_party($user))
        $past_party_groups[] = $party_group;
}

?>

<h1>
    <span>
        Upcoming Parties
    </span>
    <div class="divider"></div>
</h1>

<ul class="parties_attended">
    <?php foreach ($upcoming_party_groups as $party_group): ?>
        <li>
        <?= r('party_group', array(
                               'party_group' => $party_group,
                               'user' => $user,
                             )) ?>
        </li>
    <?php endforeach; ?>
</ul>

<h1>Past Parties</h1>
<ul class="parties_attended">
    <?php foreach ($past_party_groups as $party_group): ?>
        <li>
        <?= r('party_group', array(
                               'party_group' => $party_group,
                               'user' => $user,
                             )) ?>
        </li>
    <?php endforeach; ?>
</ul>