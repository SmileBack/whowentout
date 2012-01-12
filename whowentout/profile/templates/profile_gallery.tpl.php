<ul>
   <?php foreach ($users as $user): ?>
        <li>
            <?=
            r::profile_small(array(
                'user' => $user,
                'show_networks' => $show_networks,
                'preset' => $preset,
            ))
            ?>
        </li>
   <?php endforeach; ?>
</ul>
