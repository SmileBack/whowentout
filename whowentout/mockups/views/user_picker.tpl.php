<table class="user_picker">
    
    <tr>
        <th></th>
        <th class="pic_column"></th>
        <th class="name_column"></th>
        <th class="guestlist_column"></th>
        <th></th>
    </tr>

    <tr>
        <td>Type name:</td>
        <td></td>
        <td><input type="text"/></td>
        <td></td>
        <td>
            <a href="/">Add <?= $type ?></a>
        </td>
    </tr>

    <?php foreach ($users as $user): ?>
    <tr>
        <td></td>
        <td><img style="width: 28px; height: 28px;" src="http://profile.ak.fbcdn.net/hprofile-ak-ash2/369410_8100231_1182180090_q.jpg" /></td>
        <td><?= $user ?></td>
        <td>
            <?php if ($type == 'promoter'): ?>
            <a href="/">View Guestlist</a>
            <?php endif; ?>
        </td>
        <td>
            <a href="/">Remove <?= $type ?></a>
        </td>
    </tr>
    <?php endforeach; ?>

</table>
