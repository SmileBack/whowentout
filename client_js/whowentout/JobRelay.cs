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

            console.log("pusher key = " + _pusherKey);

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

            console.log(string.Format("queued job [{0} jobs]", queue.Count));
            console.log(jobObject);
        }

        void Queue_StatusChanged(object sender, JobQueueStatusChangedEventArgs e)
        {
            console.log(string.Format("JOB QUEUE : {0} -> {1}", e.OldStatus, e.NewStatus));
        }

        void Queue_JobComplete(object sender, JobEventArgs e)
        {
            console.log(string.Format("job complete [{0} jobs]", queue.Count));
        }

        void Queue_JobStart(object sender, JobEventArgs e)
        {
            console.log("job start");
        }

        void Channel_SubscriptionFailed(object sender, EventArgs e)
        {
            console.log("subscription failed");
        }

        void Channel_SubscriptionSucceeded(object sender, EventArgs e)
        {
            console.log("subscription succeeded");
        }

        void Connection_StateChange(object sender, StateChangeEventArgs e)
        {
            console.log(string.Format("PUSHER : {0} -> {1}", e.Previous, e.Current));
        }

    }
}
