<form class="invite_to_form" method="post" action="/invite/to">
    <?= form::hidden('user[id]', $user->id) ?>
    <?= form::hidden('event[id]', $event->id) ?>

    <?php if ($is_invited): ?>
        <div class="invited">invited</div>
    <?php endif; ?>

    <?php if (!$is_invited): ?>
        <?php if (browser::is_mobile()): ?>
			<button>Invite to <br/> <?= ($event->name) ?></button>		
		<?php else: ?>
			<button>Invite to <?= format::truncate ($event->name) ?></button>
		<?php endif; ?>
	<?php endif; ?>

</form>
