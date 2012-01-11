<ul class="profile_networks">
    <?php foreach ($user->networks->where('type', 'college') as $network): ?>
    <li><?= $network->name ?></li>
    <?php endforeach; ?>
</ul>
