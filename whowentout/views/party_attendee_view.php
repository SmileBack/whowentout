
<div id="party_attendee_<?= $attendee->id ?>" class="party_attendee" data-after="<?= isset($after) ? $after : '' ?>">
  <?= $attendee->pic; ?>

  <div class="caption">
    <p><?= $attendee->first_name; ?> <?= $attendee->last_name ?></p>
    <p><?= $attendee->college->name; ?> <?= $attendee->grad_year; ?></p>
    <p>&nbsp;<?= $attendee->hometown ?>&nbsp;</p>
    <p><?= anchor("user/mutual_friends/$attendee->id", 'Mutual Friends', array('class' => 'show_mutual_friends')) ?></p>
    <p><a class="open_chat" href="#open_chat" to="<?= $attendee->id ?>">chat</a></p>
    <p>
      <?php if ($attendee->gender != current_user()->gender): ?>
        <?php if ($attendee->was_smiled_at(current_user()->id, $party->id)): ?>
          <input type="submit" class="smiled_at submit_button" disabled="disabled"  value="Smiled at <?= $attendee->first_name ?>"></button>
        <?php else: ?>
          <?= form_open('user/smile', array('class' => 'smile_form'), array('party_id' => $party->id, 'receiver_id' => $attendee->id)); ?>
            <input type="submit" value="<?= 'Smile at ' . $attendee->first_name ?>" class="submit_button <?= $smiles_left == 0 ? 'cant' : 'can' ?>" />
          <?= form_close(); ?>
        <?php endif ?>
      <?php else: ?>
        &nbsp;
      <?php endif; ?>
    </p>
  </div>
</div>

<div id="user_json_<?= $attendee->id ?>" class="user_json" style="display: none;"><?= json_encode($attendee->to_array()) ?></div>
