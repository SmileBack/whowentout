// Connection.cs
//

using System;
using System.Collections.Generic;
using System.Runtime.CompilerServices;

namespace PusherApi
{

    public class Connection
    {

        public event EventHandler<StateChangeEventArgs> StateChange;
        public event EventHandler Connected;

        private PusherConnectionJs _connectionJs;

        public Connection(PusherConnectionJs connectionJs)
        {
            _connectionJs = connectionJs;

            _connectionJs.Bind("state_change", RelayStateChangeEvent);
        }

        public ConnectionState State
        {
            get { return JsStateToEnum(_connectionJs.State); }
        }

        private void RelayStateChangeEvent(object e)
        {
            ConnectionState current = JsStateToEnum(Type.GetField(e, "current"));
            ConnectionState previous = JsStateToEnum(Type.GetField(e, "previous"));

            if (Connected != null && current == ConnectionState.Connected)
                Connected(this, EventArgs.Empty);

            if (StateChange != null)
                StateChange(this, new StateChangeEventArgs(previous, current));
        }

        private ConnectionState JsStateToEnum(object state)
        {
            string s = (string)state;
            switch (s)
            {
                case "initialized":
                    return ConnectionState.Initialized;
                case "connecting":
                    return ConnectionState.Connecting;
                case "connected":
                    return ConnectionState.Connected;
                case "unavailable":
                    return ConnectionState.Unavailable;
                case "failed":
                    return ConnectionState.Failed;
                case "disconnected":
                    return ConnectionState.Disconnected;
                default:
                    throw new Exception("Invalid state " + s + ".");
            }
        }

    }

    [NamedValues]
    public enum ConnectionState
    {
        /// <summary>
        /// Initial state. No event is emitted in this state.
        /// </summary>
        [ScriptName("initialized")]
        Initialized = 1,

        /// <summary>
        /// All dependencies have been loaded and Pusher is trying to connect.
        /// The connection will also enter this state when it is trying to reconnect after a connection failure.
        /// </summary>
        [ScriptName("connecting")]
        Connecting = 2,

        /// <summary>
        /// The connection to Pusher is open and authenticated with your app.
        /// </summary>
        [ScriptName("connected")]
        Connected = 3,

        /// <summary>
        /// The connection is temporarily unavailable. In most cases this means
        /// that there is no internet connection. It could also mean that Pusher is down,
        /// or some intermediary is blocking the connection. In this state, Pusher will 
        /// automatically retry the connection every ten seconds.
        /// </summary>
        [ScriptName("unavailable")]
        Unavailable = 4,

        /// <summary>
        /// Pusher is not supported by the browser. This implies that Flash is not available, 
        /// since that is the only fallback in browsers that do not natively support WebSockets.
        /// </summary>
        [ScriptName("failed")]
        Failed = 5,

        /// <summary>
        /// The Pusher connection was previously connected and has now intentionally been closed.
        /// </summary>
        [ScriptName("disconnected")]
        Disconnected = 6
    }

    public class StateChangeEventArgs : EventArgs
    {
        public StateChangeEventArgs(ConnectionState previous, ConnectionState current)
        {
            this.Previous = previous;
            this.Current = current;
        }

        private ConnectionState _previous;
        public ConnectionState Previous
        {
            get { return _previous; }
            private set { _previous = value; }
        }

        private ConnectionState _current;
        public ConnectionState Current
        {
            get { return _current; }
            private set { _current = value; }
        }

    }

    [IgnoreNamespace]
    [Imported]
    public class PusherConnectionJs
    {

        public void Bind(string eventName, Action<object> handler)
        {
        }

        public string State;

    }

}
