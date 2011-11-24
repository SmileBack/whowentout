<form action="/events/view/admin">
    <fieldset>
        <legend>Basic Info</legend>

        <div>
            <label>Date</label>
            <select>
                <option>Thurs, Nov 24</option>
                <option>Fri, Nov 25</option>
                <option>Sat, Nov 26</option>
            </select>
        </div>
        
        <div>
            <label>Location</label>

            <select>
                <option>Cafe Asia</option>
                <option>Eden</option>
            </select>
        </div>

        <div>
            <label>Title</label>
            <input type="text"/>
        </div>

        <div>
            <label>Description</label>
            <textarea style="display: block; width: 400px; height: 150px;"></textarea>
        </div>

    </fieldset>

    <fieldset>
        <legend>Table Info</legend>
        <div>Table info goes here</div>
    </fieldset>

    <fieldset>
        <legend>Event Admins</legend>

        <?=
        r('user_picker', array(
                              'type' => 'admin',
                              'users' => array('Joe Schmoe', 'Schmoe Joe'),
                         ))
        ?>

    </fieldset>

    <fieldset>
        <legend>Promoters</legend>

        <?=
        r('user_picker', array(
                              'type' => 'promoter',
                              'users' => array('David Smith', 'Carls Jr.', 'Don Cheadle'),
                         ))
        ?>
    </fieldset>

    <button class="button_style">Save</button>
</form>
