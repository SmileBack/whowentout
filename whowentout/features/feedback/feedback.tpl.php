<script type="text/javascript">
document.write('<scr' + 'ipt src="' + document.location.protocol + '//fby.s3.amazonaws.com/fby.js"></scr' + 'ipt>');
</script>
<script type="text/javascript">

<?php if (auth()->logged_in()): ?>
<?= "var facebook_id = " . auth()->current_user()->facebook_id . ";" ?>
<?php endif; ?>

if (facebook_id) {
    FBY.setEmail(facebook_id + '@facebookid.com');
}

FBY.showTab({id: '1740', position: 'right', color: '#222'});
</script>