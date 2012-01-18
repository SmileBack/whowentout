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
                JobRelay relay = new JobRelay();
                relay.Start();
            });
        }

    }

}
