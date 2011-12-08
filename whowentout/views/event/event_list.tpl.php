<fieldset class="event_list">
    <legend>Check-in to claim your deal and see who else is going!</legend>
    <ul>
    <?php foreach ($events as $k => $event): ?>
        <li class="<?= $k == 0 ? 'first' : '' ?>">
            <?= r::event_option($event) ?>
        </li>
    <?php endforeach; ?>
    </ul>
</fieldset>