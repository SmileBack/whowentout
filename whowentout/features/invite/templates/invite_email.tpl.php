<h2>Hi <?= $invite->receiver->first_name ?>,</h2>

<table>
    <tr>
        <td>
            <?php
            /* @var $profile_picture ProfilePicture */
            $profile_picture = build('profile_picture', $invite->sender);
            $profile_picture_url = $profile_picture->url('thumb');
            ?>
            <img src="<?= $profile_picture_url ?>" style="width: 65px; margin-right: 20px;" alt="">
        </td>
        <td>
            <h1><?= $invite->sender->first_name ?> <?= $invite->sender->last_name ?> wants you to attend <?= $invite->event->name ?> on <?= $invite->event->date->format('l') ?></h1>
        </td>
    </tr>
</table>

<h2>Log onto <a href="<?= site_url("?invite_id=$invite->id") ?>">WhoWentOut.com</a> to check-in to <?= $invite->event->name ?> and see where everyone's going out!</h2>
