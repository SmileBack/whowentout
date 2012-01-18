// Pusher.cs
//

using System;
using System.Collections;
using System.Collections.Generic;
using System.Runtime.CompilerServices;
using WebCore;

namespace PusherApi
{

    public class PusherClient
    {

        private PusherJs _pusherJs;

        private Dictionary<string, Channel> _channels = new Dictionary<string, Channel>();

        public PusherClient(string applicationKey)
        {
            _pusherJs = new PusherJs(applicationKey);
            Connection = new Connection(_pusherJs.Connection);
        }

        private Connection _connection;
        public Connection Connection
        {
            get { return _connection; }
            private set { _connection = value; }
        }

        public Channel Subscribe(string channelName)
        {
            console.log(string.Format("subscribing to {0}", channelName));
            this._pusherJs.Subscribe(channelName);
            return GetChannel(channelName);
        }

        public void Unsubscribe(string channelName)
        {
            this._pusherJs.Unsubscribe(channelName);
            _channels.Remove(channelName);
        }

        public Channel this[string channelName]
        {
            get { return GetChannel(channelName); }
        }

        private Channel GetChannel(string channelName)
        {
            PusherChannelJs channelJs = _pusherJs.Channel(channelName);

            if (channelJs == null)
                return null;

            if (!_channels.ContainsKey(channelName))
                _channels[channelName] = new Channel(channelJs);

            return _channels[channelName];
        }

    }

    [IgnoreNamespace]
    [Imported]
    [ScriptName("Pusher")]
    public class PusherJs
    {

        public PusherJs(string applicationKey)
        {
        }

        public PusherJs(string applicationKey, Dictionary options)
        {

        }

        public void Disconnect()
        {

        }

        public PusherChannelJs Subscribe(string channelName)
        {
            return null;
        }

        public void Unsubscribe(string channelName)
        {

        }

        public PusherChannelJs Channel(string channelName)
        {
            return null;
        }

        public PusherConnectionJs Connection;

    }

}
