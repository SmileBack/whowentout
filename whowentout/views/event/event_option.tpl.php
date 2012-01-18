<label>
    <input type="radio"
           name="event_id"
           value="<?= $event->id ?>"
           <?= $selected ? 'checked="checked"' : '' ?> />
    
    <div class="place">
        <?= $event->name ?>
    </div>
    <div class="deal">
        <?= $event->deal ?>
    </div>

<div class="checkin_button <?= $selected ? 'attending' : '' ?>">
    <?php if ($selected): ?>
        attending
    <?php else: ?>
        check-in
    <?php endif; ?>
</div>

</label>
    