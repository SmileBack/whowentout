<?php
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$entourage = $entourage_engine->get_entourage($user);
$show_invite_link = isset($show_invite_link) ? $show_invite_link : false;
?>

<section class="entourage gallery">
    <h3>
        <span>
            <?= ucfirst(format::owner($user)) ?> Entourage
        </span>
        <span>(<?= count($entourage) ?>)</span>
    </h3>


    <div class="definition">
        <strong class="word">en·tou·rage</strong>
        <em class="proununciation">[ahn-too-rahzh]</em>
        <span class="part">noun</span>
        <ol>
            <li>The people you roll with</li>
        </ol>
    </div>

    <h2>
        <?= ucfirst(format::first_name($user)) ?>
        <?= format::pov('has', $user) ?>
        0 people in
        <?= format::pov('his', $user) ?> entourage.
    </h2>

    <?php if ($show_invite_link): ?>
        <a class="entourage_request_link action" href="/entourage/invite">Send Entourage Request</a>
    <?php endif; ?>

    <?php if (count($entourage) > 0): ?>
        <?= r::profile_gallery(array('users' => $entourage, 'preset' => 'thumb', 'link_to_profile' => true)) ?>
    <?php else: ?>

    <?php endif; ?>

</section>
