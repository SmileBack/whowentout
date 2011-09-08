<?php if ( empty($parties_attended) ): ?>
  <h2>The parties you checkin to will appear here</h2>
<?php else: ?>
  <ul class="parties_attended">

    <? foreach ($parties_attended as $party): ?>						
    <li>

      <div class="party_summary">
        <h2 class="date"><?= date("l, F jS", strtotime($party->date)); ?></h2>
        <div class="divider">|</div>
        <div class="place"><?= anchor("party/{$party->id}", $party->place->name); ?></div>

        <div class="notices">
          <span class="smiles_received"><?= $user->smiles_received_message($party->id) ?></span>
          <span class="smiles_left"><?= $user->smiles_left_message($party->id) ?></span>
          <ul class="smile_matches">
            <? foreach ($user->matches($party) as $match ): ?>
              <li>
                You and <?= $match->other_user->full_name ?> have smiled at each other!
                <?= $match->other_user->anchor_facebook_message() ?>
              </li>
            <? endforeach; ?>
          </ul>
        </div>

      </div>

    </li>
    <? endforeach; ?>

  </ul>
<?php endif; ?>