<?php if ( empty($parties_attended) ): ?>
  <h2>The parties you checkin to will appear here</h2>
<?php else: ?>
  <ul class="parties_attended">

    <? foreach ($parties_attended as $party): ?>						
    <li>

      <div class="party_summary">
        <div class="date"><?= date("l, F jS", strtotime($party->date)); ?></div>
        <div class="divider">|</div>
        <div class="place"><?= anchor("party/{$party->id}", $party->place->name); ?></div>

        <div class="smiles">
          <span class="received"><?= $user->smiles_received_message($party->id) ?></span>
          <span class="remaining"><?= $user->smiles_left_message($party->id) ?></span>
          <ul class="matches">
            <? foreach ($user->matches($party->id) as $match ): ?>
              <li>
                You and <?= $match->first_name; ?> <?= $match->last_name ?> have smiled at each other!
                <?= $match->anchor_facebook_message() ?>
              </li>
            <? endforeach; ?>
          </ul>
        </div>

      </div>

    </li>
    <? endforeach; ?>

  </ul>
<?php endif; ?>