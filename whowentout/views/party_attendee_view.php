<?php
if (!isset($smiles_left))
    $smiles_left = 0;
?>

<div id="party_attendee_<?= $attendee->id ?>" class="party_attendee user <?= 'user_' . $attendee->id ?>" data-user-id="<?= $attendee->id ?>">
    <?php if (TRUE): ?>
    <?= $attendee->pic; ?>
    <?php endif; ?>

    <div class="caption">

        <div class="full_name" to="<?= $attendee->id ?>">
            <div><?= $attendee->first_name; ?> <?= $attendee->last_name ?></div>
            <div class="online_badge"></div>
        </div>

        <?php if ($attendee->college): ?>
        <p><?= $attendee->college->name; ?> <?= $attendee->grad_year; ?></p>
        <?php endif; ?>

        <?php if (!$attendee->college): ?>
        <p>&nbsp;</p>
        <?php endif; ?>

        <p>&nbsp;<?= $attendee->hometown ?>&nbsp;</p>

        <p>
            <?= anchor("user/mutual_friends/$attendee->id", 'Mutual Friends', array('class' => 'show_mutual_friends')) ?>
        </p>

        <?php if (TRUE): ?>
        <p>
            <?php if ($attendee->gender != current_user()->gender): ?>
            <?php if ($attendee->was_smiled_at(current_user()->id, $party->id)): ?>
                <input type="submit" class="smiled_at submit_button" disabled="disabled"
                       value="Smiled at <?= $attendee->first_name ?>"></button>
                <?php elseif ($party->smiling_is_open()): ?>

                <?= form_open('user/smile', array('class' => 'smile_form'), array('party_id' => $party->id, 'receiver_id' => $attendee->id)) ; ?>

                <input type="submit" value="<?= 'Smile at ' . $attendee->first_name ?>"
                       class="submit_button <?= $smiles_left == 0 ? 'cant' : 'can' ?>"/>

                <?= form_close(); ?>
                <?php endif ?>
            <?php else: ?>
                &nbsp;
            <?php endif; ?>
        </p>
        <?php endif; ?>

    </div>

</div>
    