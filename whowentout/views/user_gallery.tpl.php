<ul class="user_gallery">
    <?php foreach ($users as $user): ?>
        <li><?= $user->first_name . ' ' . $user->last_name ?></li>
    <?php endforeach; ?>
</ul>
