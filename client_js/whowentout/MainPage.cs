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
                JobQueue queue = new JobQueue();
                queue.JobStart += new EventHandler<JobEventArgs>(Queue_JobStart);
                queue.JobComplete += new EventHandler<JobEventArgs>(Queue_JobComplete);
                queue.StatusChanged += new EventHandler<JobQueueStatusChangedEventArgs>(Queue_StatusChanged);

                PusherClient p = new PusherClient("805af8a6919abc9fb047");
                p.Connection.StateChange += new EventHandler<StateChangeEventArgs>(Connection_StateChange);

                Channel c = p.Subscribe("job_queue");
                c.SubscriptionSucceeded += new EventHandler(Channel_SubscriptionSucceeded);
                c.SubscriptionFailed += new EventHandler(Channel_SubscriptionFailed);

                c.Bind("new_job", delegate(object jobObj)
                {
                    console.log(jobObj);
                    string url = (string)Type.GetField(jobObj, "url");
                    SendRequestJob job = new SendRequestJob(url);
                    queue.Add(job);
                });
            });
        }

        static void Queue_StatusChanged(object sender, JobQueueStatusChangedEventArgs e)
        {
            console.log(string.Format("JOB QUEUE : {0} -> {1}", e.OldStatus, e.NewStatus));
        }

        static void Queue_JobComplete(object sender, JobEventArgs e)
        {
            console.log("job complete");
        }

        static void Queue_JobStart(object sender, JobEventArgs e)
        {
            console.log("job start");
        }

        static void Channel_SubscriptionFailed(object sender, EventArgs e)
        {
            console.log("subscription failed");
        }

        static void Channel_SubscriptionSucceeded(object sender, EventArgs e)
        {
            console.log("subscription succeeded");
        }

        static void Connection_StateChange(object sender, StateChangeEventArgs e)
        {
            console.log(string.Format("PUSHER : {0} -> {1}", e.Previous, e.Current));
        }

    }

}
