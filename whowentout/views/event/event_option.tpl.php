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
    <div class="help">
        check-in
    </div>
</label>
    