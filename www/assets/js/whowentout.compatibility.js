//= require lib/getflashplayerversion.js
//= require lib/jquery.js
//= require widgets/jquery.dialog.js
//= require whowentout.component.js

WhoWentOut.Component.extend('WhoWentOut.Compatibility', {
    init: function() {
        
    },
    flashPlayerIsOutOfDate: function() {
        var version = getFlashPlayerVersion();
        return version[0] < 10;
    },
    flashPlayerIsInstalled: function() {
        return !!getFlashPlayerVersion();
    },
    showInstallFlashDialog: function() {
        var dialog = $.dialog.create({centerInViewport: true});
        $.dialog.hideMaskOnClick(false);

        dialog.title('Flash Player required.');
        dialog.message(
        '<p>Download Flash Player to use WhoWentOut.</p>'
        + '<p><a href="http://get.adobe.com/flashplayer/" target="_blank"><img src="/assets/images/get_flash_player_button.jpg" /></a></p>'
        );

        dialog.showDialog();
    },
    showUpgradeFlashDialog: function() {
        var dialog = $.dialog.create({centerInViewport: true});
        $.dialog.hideMaskOnClick(false);

        dialog.title('Flash Player is Out of Date');
        dialog.message(
        '<p>Upgrade Flash Player to use WhoWentOut.</p>'
        + '<p><a href="http://get.adobe.com/flashplayer/" target="_blank"><img src="/assets/images/get_flash_player_button.jpg" /></a></p>'
        );

        dialog.showDialog();
    }
});
