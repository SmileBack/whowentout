<?php
/* @var $party XParty */
?>

<div class="pictures_teaser">
    <div>
        
    </div>
    <h1>
        <?= anchor("party/pictures/{$party->id}", "View {$party->place->name} Photos") ?>
    </h1>
</div>
