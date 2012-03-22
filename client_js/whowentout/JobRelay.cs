// JobRelay.cs
//

using System;
using System.Collections.Generic;
using whowentout.lib;
using WebCore;
using PusherApi;

namespace whowentout
{
    public class JobRelay
    {

        private JobQueue queue;
        private PusherClient pusher;
        private Channel channel;

        private bool _started = false;

        private string _pusherKey;

        public JobRelay(string pusherKey)
        {
            _pusherKey = pusherKey;
        }

        private void Log(object message)
        {
            string time = Date.Now.ToLocaleDateString() + " " + Date.Now.ToLocaleTimeString();
            console.log(string.Format("{0} : {1}", time, message));
        }

        internal void Start()
        {
            if (_started)
                return;

            _started = true;

            queue = new JobQueue();
            queue.JobStart += new EventHandler<JobEventArgs>(Queue_JobStart);
            queue.JobComplete += new EventHandler<JobEventArgs>(Queue_JobComplete);
            queue.StatusChanged += new EventHandler<JobQueueStatusChangedEventArgs>(Queue_StatusChanged);

            pusher = new PusherClient(_pusherKey);
            pusher.Connection.StateChange += new EventHandler<StateChangeEventArgs>(Connection_StateChange);

            Log("pusher key = " + _pusherKey);

            channel = pusher.Subscribe("job_queue");
            channel.SubscriptionSucceeded += new EventHandler(Channel_SubscriptionSucceeded);
            channel.SubscriptionFailed += new EventHandler(Channel_SubscriptionFailed);

            channel.Bind("new_job", OnNewJobReceived);
        }

        void OnNewJobReceived(object jobObject)
        {
            string url = (string)Type.GetField(jobObject, "url");
            SendRequestJob job = new SendRequestJob(url);
            queue.Add(job);

            Log(string.Format("queued job [{0} jobs]", queue.Count));
            console.log(jobObject);
        }

        void Queue_StatusChanged(object sender, JobQueueStatusChangedEventArgs e)
        {
            Log(string.Format("JOB QUEUE : {0} -> {1}", e.OldStatus, e.NewStatus));
        }

        void Queue_JobComplete(object sender, JobEventArgs e)
        {
            Log(string.Format("job complete [{0} jobs]", queue.Count));
        }

        void Queue_JobStart(object sender, JobEventArgs e)
        {
            Log("job start");
        }

        void Channel_SubscriptionFailed(object sender, EventArgs e)
        {
            Log("subscription failed");
        }

        void Channel_SubscriptionSucceeded(object sender, EventArgs e)
        {
            Log("subscription succeeded");
        }

        void Connection_StateChange(object sender, StateChangeEventArgs e)
        {
            Log(string.Format("PUSHER : {0} -> {1}", e.Previous, e.Current));
        }

    }
}
