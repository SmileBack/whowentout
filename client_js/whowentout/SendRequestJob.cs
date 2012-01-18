// SendRequestJob.cs
//

using System;
using System.Collections.Generic;
using whowentout.lib;
using jQueryApi;

namespace whowentout
{
    public class SendRequestJob : Job
    {

        private string _url;

        public SendRequestJob(string url)
        {
            _url = url;
        }

        public override object Run()
        {
            return jQuery.Ajax(_url);
        }

    }
}
