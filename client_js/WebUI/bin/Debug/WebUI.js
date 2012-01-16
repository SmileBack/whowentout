// WebUI.js
(function(){
Type.registerNamespace('WebUI');WebUI.Dialog=function(){}
WebUI.Dialog.show=function(title,body){if(WebUI.Dialog.$0==null){WebUI.Dialog.$0=$.dialog.create();}WebUI.Dialog.$0.title(title);WebUI.Dialog.$0.message(body);WebUI.Dialog.$0.showDialog();}
WebUI.Dialog.hide=function(){if(WebUI.Dialog.$0==null){return;}WebUI.Dialog.$0.hideDialog();}
WebUI.Dialog.registerClass('WebUI.Dialog');WebUI.Dialog.$0=null;})();// This script was generated using Script# v0.7.4.0
