
<ul class="parties_attended">

  <? foreach ($parties_attended as $party): ?>						
  <li>

    <div class="party_summary">
      <!-- Move to a helper -->                                      
      <div class="date"><?= date("l, F jS", strtotime($party->date)); ?></div>
      | <div class="place"><?= anchor("party/{$party->id}", $party->place->name); ?></div>

      <!-- TODO: check gender -->
      <div class="smiles">
        <span class="smiles_received"><?= $user->smiles_received_message($party->id) ?></span>
        <span class="smiles_remaining"><?= $user->smiles_left_message($party->id) ?></span>
        <ul class="matches">
          <? foreach ($user->matches($party->id) as $match ): ?>
            <li>You and <?= $match->first_name; ?> <?= $match->last_name ?> have smiled at each other!</li>
          <? endforeach; ?>
        </ul>
      </div>
      
      <!-- TODO: Change to singular if one smile is left -->
    
    </div>

  </li>
  <? endforeach; ?>

</ul>


