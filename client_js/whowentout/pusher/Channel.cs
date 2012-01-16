// Channel.cs
//

using System;
using System.Collections.Generic;
using WebCore;
using System.Runtime.CompilerServices;
using System.Serialization;

namespace PusherApi
{
    public class Channel
    {

        private PusherChannelJs _channelJs;

        public Channel(PusherChannelJs channelJs)
        {
            _channelJs = channelJs;

            _channelJs.Bind("pusher:subscription_succeeded", delegate(object e)
            {
                console.log("succeeded****");
            });

            _channelJs.Bind("pusher:subscription_failed", delegate(object e)
            {
                console.log("failed***");
            });
        }

        public void Bind(string eventName, Action<object> handler)
        {
            _channelJs.Bind(eventName, handler);
        }

        public bool Trigger(string eventName, string data)
        {
            return _channelJs.Trigger(eventName, data);
        }

    }

    [IgnoreNamespace]
    [Imported]
    public class PusherChannelJs
    {
        public void Bind(string eventName, Action<object> handler)
        {
        }

        public bool Trigger(string eventName, object eventData)
        {
            return false;
        }
    }

}
