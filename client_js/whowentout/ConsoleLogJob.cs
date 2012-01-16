// ConsoleLogJob.cs
//

using System;
using System.Collections.Generic;
using whowentout.lib;
using WebCore;

namespace whowentout
{
    public class ConsoleLogJob : Job
    {

        private string _message;

        public ConsoleLogJob(string message)
        {
            _message = message;
        }

        public override object Run()
        {
            console.log(_message);
            return null;
        }

    }
}
