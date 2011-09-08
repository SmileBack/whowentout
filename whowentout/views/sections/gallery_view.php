
<div class="notices">
  <p class="smiles_received"><?= $user->smiles_received_message($party->id) ?></p>
  <p class="smiles_left"><?= $user->smiles_left_message($party->id) ?></p>

  <ul class="smile_matches">
    <? foreach ($user->matches($party->id) as $match ): ?>
      <li>
        You and <?= $match->first_name; ?> <?= $match->last_name ?> have smiled at each other!
        <?= $match->anchor_facebook_message() ?>
      </li>
    <? endforeach; ?>
  </ul>
</div>
  
<div class="sortbar">
  <h2>Sort by:</h2>
  <ul>
    <li class="sort_checkin_time <?= $sort == 'checkin_time' ? 'selected' : '' ?>">
      <?= anchor("party/$party->id?sort=checkin_time", "Checkin Time") ?>
    </li>
    <li class="sort_first_name <?= $sort == 'name' ? 'selected' : '' ?>">
      <?= anchor("party/$party->id?sort=name", "Name") ?>
    </li>
    <li class="sort_gender <?= $sort == 'gender' ? 'selected' : '' ?>">
      <?= anchor("party/$party->id?sort=gender", "Gender") ?>
    </li>
  </ul>
</div>

<div class="gallery serverevents"
     channel-id="<?= 'party_' . $party->id ?>"
     channel-url="<?= serverchannel_url('party', $party->id) ?>"
     data-sort="<?= $sort ?>" data-party-id="<?= $party->id ?>" data-count="<?= $party->count ?>">
  <ul>
    <?php foreach ($party_attendees as $key => $attendee): ?>
      <li>
        <?= 
          load_view('party_attendee_view', array(
            'party' => $party,
            'attendee' => $attendee,
            'smiles_left' => $smiles_left,
          ))
        ?>
      </li>
    <?php endforeach; ?>
  </ul>
  
</div>

<?php if ($party->admin): ?>
  <p id="party_admin">Hosted by: <?= $party->admin->first_name; ?> <?= $party->admin->last_name; ?></p>
<?php endif;?>
