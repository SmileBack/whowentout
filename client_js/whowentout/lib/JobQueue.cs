// JobQueue.cs
//

using System;
using System.Collections.Generic;
using System.Html;
using jQueryApi;
using WebCore;
using System.Runtime.CompilerServices;

namespace whowentout.lib
{

    [NamedValues]
    public enum JobQueueStatus
    {
        [ScriptName("idle")]
        Idle = 1,

        [ScriptName("busy")]
        Busy = 2
    }

    public class JobQueue
    {

        public event EventHandler<JobEventArgs> JobStart;
        public event EventHandler<JobEventArgs> JobComplete;
        public event EventHandler<JobQueueStatusChangedEventArgs> StatusChanged;

        private Queue<Job> _tasks = new Queue<Job>();
        private Job _currentJob = null;

        public JobQueue()
        {
        }

        public Job CurrentJob
        {
            get { return _currentJob; }
        }

        /// <summary>
        /// The number of Jobs remaining in the queue.
        /// The job currently being executed is not included.
        /// </summary>
        public int Count
        {
            get { return _tasks.Count; }
        }

        private JobQueueStatus _status = JobQueueStatus.Idle;
        public JobQueueStatus Status
        {
            get { return _status; }
            private set
            {
                if (_status != value)
                {
                    JobQueueStatus oldStatus = _status;
                    JobQueueStatus newStatus = value;
                    _status = newStatus;
                    if (StatusChanged != null)
                        StatusChanged(this, new JobQueueStatusChangedEventArgs(oldStatus, newStatus));
                }
            }
        }

        public void Clear()
        {
            _tasks.Clear();
        }

        public void Add(Job job)
        {
            _tasks.Enqueue(job);

            Run();
        }

        public void Run()
        {
            if (Status == JobQueueStatus.Busy)
                return;

            Status = JobQueueStatus.Busy;
            ProcessNextItemInQueue();
        }

        private void ProcessNextItemInQueue()
        {
            if (Count == 0)
            {
                Status = JobQueueStatus.Idle;
                return;
            }

            _currentJob = _tasks.Dequeue();

            if (JobStart != null)
                JobStart(this, new JobEventArgs(_currentJob));

            object result = _currentJob.Run();

            if (IsDeferredObject(result))
            {
                jQueryDeferred dfd = (jQueryDeferred)result;
                Action onCompleteAction = new Action(delegate()
                {
                    _currentJob = null;
                    if (JobComplete != null)
                        JobComplete(this, new JobEventArgs(_currentJob));

                    Window.SetTimeout(ProcessNextItemInQueue, 0);
                });
                dfd.Then(onCompleteAction, onCompleteAction);
            }
            else
            {
                Window.SetTimeout(ProcessNextItemInQueue, 0);
            }
        }

        private bool IsDeferredObject(object o)
        {
            return o != null && Type.GetField(o, "then") != null;
        }

    }

    public class JobEventArgs : EventArgs
    {

        private Job _job;

        public JobEventArgs(Job job)
        {
            _job = job;
        }

        public Job Job
        {
            get { return _job; }
        }

    }

    public class JobQueueStatusChangedEventArgs : EventArgs
    {

        private JobQueueStatus _oldStatus;
        private JobQueueStatus _newStatus;

        public JobQueueStatusChangedEventArgs(JobQueueStatus oldStatus, JobQueueStatus newStatus)
        {
            _oldStatus = oldStatus;
            _newStatus = newStatus;
        }

        public JobQueueStatus OldStatus
        {
            get { return _oldStatus; }
        }

        public JobQueueStatus NewStatus
        {
            get { return _newStatus; }
        }

    }

}
