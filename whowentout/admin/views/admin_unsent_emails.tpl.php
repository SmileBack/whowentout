<?php
$jobs = db()->table('jobs')
        ->where('type', 'SendEmailJob')
        ->where('status', array('pending', 'running'));
?>
<style type="text/css">
    .admin_unsent_emails {
        margin: 25px;
    }

    .admin_unsent_emails dd {
        margin-left: 15px;
    }

    .admin_unsent_emails li {
        padding: 12px;
        border: 1px solid grey;
    }
</style>
<div class="admin_unsent_emails">
    <h1>Unsent Email Jobs</h1>
    <ul>
        <?php foreach ($jobs as $job): ?>
        <?php
        $hash = substr($job->id, 0, 5);
        $opts = (object)unserialize($job->options);

        $user = db()->table('users')->row($opts->user_id);
        $subject = $opts->subject;
        $body = $opts->body;
        ?>
        <li>

                <dl>
                    <dt>id</dt>
                    <dd><?= $hash ?></dd>

                    <dt>status</dt>
                    <dd><?= $job->status ?></dd>

                    <dt>user</dt>
                    <dd>
                        <?= "$user->first_name $user->last_name" ?>
                    </dd>

                    <dt>facebook id</dt>
                    <dd>
                        <?= $user->facebook_id ?>
                    </dd>

                    <dt>email</dt>
                    <dd>
                        <form method="post" action="/admin/emails/update">
                            <input type="hidden" name="user[id]" value="<?= $user->id ?>" />
                            <input type="text" name="user[email]" value="<?= $user->email ?>" />
                            <input type="submit" name="op" value="update email" />
                        </form>
                    </dd>

                    <dt>subject</dt>
                    <dd><?= $subject ?></dd>

                    <?php if (false): ?>
                        <dt>body</dt>
                        <dd><?= $body ?></dd>
                    <?php endif; ?>
                </dl>

                <form method="post" action="/admin/emails/run_job">
                    <input type="hidden" name="job_id" value="<?= $job->id ?>" />
                    <input type="submit" name="op" value="run job" />
                </form>

        </li>
        <?php endforeach; ?>
    </ul>
</div>