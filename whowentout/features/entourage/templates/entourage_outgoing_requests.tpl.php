<ul>
   <?php foreach ($requests as $request): ?>
        <li>
            <?=
            r::profile_small(array(
                'user' => $request->receiver,
                'show_networks' => false,
                'link_to_profile' => true,
            ))
            ?>
        </li>
   <?php endforeach; ?>
</ul>

