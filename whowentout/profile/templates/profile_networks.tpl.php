<ul class="profile_networks">
    <?php foreach ($user->networks as $network): ?>
    <li><?= $network->name ?></li>
    <?php endforeach; ?>
</ul>
