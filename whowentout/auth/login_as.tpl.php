<?php foreach (db()->table('users')->limit(100) as $user): ?>
    <li><?= r::profile_small(array('user' => $user, 'preset' => 'facebook.square')) ?></li>
<?php endforeach; ?>