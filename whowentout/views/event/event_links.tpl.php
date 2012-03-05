<ul class="event_links">
    <?php foreach ($events as $event): ?>
    <li>
        <a href="<?= $event->id ?>">
            <span class="name"><?= $event->name ?></span>
            <span class="count">(<?= $event->count ?>)</span>
        </a>
    </li>
    <?php endforeach; ?>
</ul>
