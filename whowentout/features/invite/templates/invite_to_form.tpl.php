<form class="invite_to_form" method="post" action="/invite/to">
    <?= form::hidden('user[id]', $user->id) ?>
    <?= form::hidden('event[id]', $event->id) ?>

    <?php if ($is_invited): ?>
        <div class="invited">invited</div>
    <?php endif; ?>

    <?php if (!$is_invited): ?>
        <button>Invite to </br> <?= /* format::truncate */ ($event->name) ?></button>
    <?php endif; ?>

</form>
