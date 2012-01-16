using System;
using System.Collections.Generic;
using System.Html;
using System.Runtime.CompilerServices;
using jQueryApi;

using WebCore;
using WebUI;
using whowentout.lib;
using PusherApi;

namespace whowentout
{

    [GlobalMethods]
    internal static class MainPage
    {

        static MainPage()
        {
            PusherClient p = new PusherClient("805af8a6919abc9fb047");

            p.Connection.StateChange += new EventHandler<StateChangeEventArgs>(Connection_StateChange);
            p.Subscribe("woo");

            p["woo"].Bind("stuff", delegate(object e)
            {
                console.log(e);
            });

            jQuery.Select("a").Live("click", delegate(jQueryEvent e)
            {
                //p["woo"].Trigger("client-link_click", e.
            });
        }

        static void Connection_StateChange(object sender, StateChangeEventArgs e)
        {
            console.log(e.Current.ToString());
        }

    }

}
