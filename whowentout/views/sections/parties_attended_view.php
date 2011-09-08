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

          <?= load_view('party_notices_view', array(
                                                   'user' => $user,
                                                   'party' => $party,
                                              )); ?>

      </div>

    </li>
    <? endforeach; ?>

  </ul>
<?php endif; ?>