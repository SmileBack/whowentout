<h2>Hi <?= $request->receiver->first_name ?>,</h2>

<table>
    <tr>
        <td>
            <?php
            /* @var $profile_picture ProfilePicture */
            $profile_picture = build('profile_picture', $request->sender);
            $profile_picture_url = $profile_picture->url('thumb');
            ?>
            <img src="<?= $profile_picture_url ?>" style="width: 65px; margin-right: 20px;" alt="">
        </td>
        <td>
            <h1><?= $request->sender->first_name ?> <?= $request->sender->last_name ?> wants you to join <?= Inflect::genderize($request->sender->gender, 'his') ?> entourage.</h1>
        </td>
    </tr>
</table>

<h2><a href="<?= site_url('/') ?>">Click here</a> to go to <a href="<?= site_url('/') ?>">WhoWentOut.com</a> and accept <?= Inflect::genderize($request->sender->gender, 'his') ?> request!</h2>
