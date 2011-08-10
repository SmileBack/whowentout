
<?php if (empty($mutual_friends)): ?>
  <h2 class="no_friends_in_common">
    You have no friends in common.
  </h2>
<?php else: ?>

  <?php if (current_user() == $target): ?>
  <div class="message">
    You have a lot of mutual friends with yourself ;)
  </div>
  <?php endif; ?>

  <ul class="mutual_friends">
    <?php foreach ($mutual_friends as $friend): ?>
      <li>
        <div class="thumb">
          <?= img($friend->thumb) ?>
        </div>
        <div class="full_name">
          <?= $friend->full_name ?>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endif; ?>
