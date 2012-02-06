<?php
/* @var $entourage_engine EntourageEngine */
$entourage_engine = build('entourage_engine');
$entourage = $entourage_engine->get_entourage($user);
$show_invite_link = isset($show_invite_link) ? $show_invite_link : false;
?>

<section class="entourage gallery">
    <h3>
        <span>Entourage (<?= count($entourage) ?>)</span>

        <?php if ($show_invite_link): ?>
            <a class="entourage_request_link action" href="/entourage/invite">Send Entourage Request</a>
        <?php endif; ?>
    </h3>
    <?php if (count($entourage) > 0): ?>
        <?= r::profile_gallery(array('users' => $entourage, 'preset' => 'thumb', 'link_to_profile' => true)) ?>
    <?php else: ?>

        <div class="definition">
            <strong class="word">en·tou·rage</strong>
            <em class="proununciation">[ahn-too-rahzh]</em>
            <span class="part">noun</span>
            <ol>
                <li>The people you roll with</li>
            </ol>
        </div>

        <h2>
            <?= $user->first_name ?> has 0 people in his entourage.
        </h2>
    <?php endif; ?>

</section>
