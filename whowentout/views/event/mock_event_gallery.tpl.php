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
<div class="event_gallery">
    <h1>McFadden's Attendees</h1>
    <ul>
        <?php for ($n = 1; $n <= 18; $n++): ?>
            <?php $image_url = "/images/mockup_profiles/thumb{$n}.jpg" ?>
        <li>
            <img src="<?= $image_url ?>" />
            <div class="user_full_name"><?= $names[$n] ?></div>
            <a class="send_message_link" href="/yea">send message</a>
        </li>
        <?php endfor; ?>
    </ul>
</div>
