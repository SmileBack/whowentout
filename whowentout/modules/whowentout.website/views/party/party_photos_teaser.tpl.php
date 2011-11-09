<?php
/* @var $party XParty */
$photo_gallery = $party->get_photo_gallery();
?>

<div class="pictures_teaser">
    <ul>
        <?php for ($i = 0; $i < 4; $i++): ?>
            <li>
                <?= anchor("party/pictures/{$party->id}", img($photo_gallery->picture($i)->url('thumb'))) ?>
            </li>
        <?php endfor; ?>
    </ul>
    <h1>
        <?= anchor("party/pictures/{$party->id}", "View Rest of {$party->place->name} Photos") ?>
    </h1>
</div>
