
<p><?= $user->smiles_received_message($party->id) ?></p>
<p><?= $user->smiles_left_message($party->id) ?></p>

<? foreach ($user->matches($party->id) as $match): ?>
  <p>
    You and <?= $match->full_name; ?>  have smiled at each other!
    <?= $match->anchor_facebook_message() ?>
  </p>
<? endforeach; ?>

<h3>Sorting</h3>
<ul class="sortbar">
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

<ul class="gallery" data-sort="<?= $sort ?>" data-party-id="<?= $party->id ?>" data-count="<?= $party->count ?>">
  
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

<?php if ($party->admin): ?>
  <p id="party_admin">Hosted by: <?= $party->admin->first_name; ?> <?= $party->admin->last_name; ?></p>
<?php endif;?>
