<div class="party_notices" for="<?= $party->id ?>">
  <p class="smiles_received"><?= $user->smiles_received_message($party->id) ?></p>
  <p class="smiles_left"><?= $user->smiles_left_message($party->id) ?></p>

  <ul class="smile_matches">
    <? foreach ($user->matches($party) as $match ): ?>
      <li>
        You and <?= $match->other_user($user)->full_name ?> have smiled at each other!
        <?= $match->other_user($user)->anchor_facebook_message() ?>
      </li>
    <? endforeach; ?>
  </ul>
</div>
    