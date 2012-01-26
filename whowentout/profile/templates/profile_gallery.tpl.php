<ul>
   <?php foreach ($users as $user): ?>
        <li>
            <?=
            r::profile_small(array(
                'user' => $user,
                'show_networks' => $show_networks,
                'preset' => $preset,
                'link_to_profile' => $link_to_profile,
            ))
            ?>
        </li>
   <?php endforeach; ?>
</ul>
