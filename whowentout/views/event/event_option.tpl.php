<label>
    <input type="radio"
           name="event_id"
           value="<?= $event->id ?>"
           <?= $selected ? 'checked="checked"' : '' ?> />
    
    <div class="place">
        <?= $event->name ?>
    </div>
    <div class="deal">
        <?php if ($selected && $event->deal): ?>
            <?= r::show_deal_link(array('event' => $event)); ?>
        <?php else: ?>
            <?= $event->deal ?>
        <?php endif; ?>
    </div>

<div class="checkin_button <?= $selected ? 'active' : '' ?>">
    <?php if ($selected): ?>
        attending
    <?php else: ?>
        check-in
    <?php endif; ?>
</div>

</label>
    