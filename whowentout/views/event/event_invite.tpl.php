<?php
$names = "
Jessica W.
Ashley L.
Brittany R.
Michael B.
Chris C.
Amanda G.
Matt H.
Samantha D.
Sarah B.
Josh M.
Daniel A.
David S.
Stephanie C.
Andrew E.
James W.
Joseph R.
Jennifer T.
John Y.
Brian Z.
Ryan B.
Lauren G.
Megan H.
Robert J.
Anthony N.
William M.
Emily K.
Nicole L.
Kyle P.
Rebecca C.
Michelle S.
Tiffany T.
Chelsea B.
Christina R.
Katherine B.
Eric V.
Katie D.
Steven K.";
$names = preg_split('/\s*\n\s*/', $names);
?>

<?php srand(1) ?>
<fieldset class="event_invite">
    <legend>
        <h1>Tell your Friends about the Deal!</h1>
    </legend>
    <ul>
        <?php for ($n = 19; $n <= 37; $n++): ?>
        <?php $image_url = "/images/mockup_profiles/thumb{$n}.jpg" ?>
        <li>
            <img src="<?= $image_url ?>"/>
            <div class="user_full_name"><?= $names[$n] ?></div>
            <?php $toss = rand(0, 5) ?>
            <?php if ($toss == 2): ?>
                <a class="event_invited_badge" href="/yea">invited</a>
            <?php elseif ($toss == 1): ?>
                <a class="event_attending_badge" href="/yea">attending</a>
            <?php else: ?>
                <a class="event_invite_link" href="/yea">invite</a>
            <?php endif; ?>

        </li>
        <?php endfor; ?>
    </ul>
</fieldset>
