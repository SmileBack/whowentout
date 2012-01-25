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

    <div class="badge">
        <?php if ($selected && $event->deal): ?>
            <?= r::show_deal_link(array('event' => $event)) ?>
        <?php elseif ($selected && !$event->deal): ?>
            <div class="attending_badge pressed">attending</div>
        <?php else: ?>
            <div class="checkin_badge">check-in</div>
        <?php endif; ?>
    </div>

</label>
    