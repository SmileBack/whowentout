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
            jQuery.OnDocumentReady(delegate()
            {
                string pusherKey = (string)Type.GetField(Window.Self, "pusher_key");
                JobRelay relay = new JobRelay(pusherKey);
                relay.Start();
            });
        }

    }

}
