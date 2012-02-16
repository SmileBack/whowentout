<ul class="event_links">
    <?php foreach ($link_data as $l): ?>
    <li>
        <a href="<?= $l->id ?>">
            <span class="name"><?= db()->table('events')->row($l->id)->name ?></span>
            <span class="count">(<?= $l->count ?>)</span>
        </a>
    </li>
    <?php endforeach; ?>
</ul>