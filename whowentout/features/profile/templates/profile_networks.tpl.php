<?php
/* @var $network_source NetworkSource */
$network_source = build('network_source');
$networks = $network_source->get_network_names($user->id);
?>
<ul class="profile_networks">
    <?php foreach ($networks as $network): ?>
    <li><?= $network ?></li>
    <?php endforeach; ?>
</ul>
