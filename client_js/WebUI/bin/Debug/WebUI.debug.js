//! WebUI.debug.js
//

(function() {

Type.registerNamespace('WebUI');

////////////////////////////////////////////////////////////////////////////////
// WebUI.Dialog

WebUI.Dialog = function WebUI_Dialog() {
    /// <field name="_dlg" type="DialogObject" static="true">
    /// </field>
}
WebUI.Dialog.show = function WebUI_Dialog$show(title, body) {
    /// <param name="title" type="String">
    /// </param>
    /// <param name="body" type="String">
    /// </param>
    if (WebUI.Dialog._dlg == null) {
        WebUI.Dialog._dlg = $.dialog.create();
    }
    WebUI.Dialog._dlg.title(title);
    WebUI.Dialog._dlg.message(body);
    WebUI.Dialog._dlg.showDialog();
}
WebUI.Dialog.hide = function WebUI_Dialog$hide() {
    if (WebUI.Dialog._dlg == null) {
        return;
    }
    WebUI.Dialog._dlg.hideDialog();
}


WebUI.Dialog.registerClass('WebUI.Dialog');
WebUI.Dialog._dlg = null;
})();

//! This script was generated using Script# v0.7.4.0
