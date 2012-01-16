// Class1.cs
//

using System;
using System.Html;
using System.Runtime.CompilerServices;
using jQueryApi;

namespace WebUI
{

    public static class Dialog
    {
        private static DialogObject dlg = null;

        public static void Show(string title, string body)
        {
            if (dlg == null)
                dlg = DialogInterop.create();

            dlg.title(title);
            dlg.message(body);
            dlg.showDialog();
        }

        public static void Hide()
        {
            if (dlg == null)
                return;

            dlg.hideDialog();
        }

    }

    [IgnoreNamespace]
    [Imported]
    [ScriptName("$.dialog")]
    static class DialogInterop
    {
        public static DialogObject create()
        {
            return null;
        }
    }

    [IgnoreNamespace]
    [Imported]
    class DialogObject
    {

        public string title()
        {
            return null;
        }

        public string title(string title)
        {
            return null;
        }

        public string message()
        {
            return null;
        }

        public string message(string message)
        {
            return null;
        }

        public void showDialog()
        {
        }

        public void hideDialog()
        {
        }

    }

}
