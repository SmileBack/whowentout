// CountJob.cs
//

using System;
using System.Collections.Generic;
using jQueryApi;
using System.Html;

using WebCore;
using whowentout.lib;

namespace whowentout
{

    public class CountJob : Job
    {

        private int _target;
        private int _cur;
        private string _name;

        public CountJob(string name, int n)
        {
            _name = name;
            _target = n;
            _cur = 0;
        }

        public override object Run()
        {
            jQueryDeferred dfd = jQuery.Deferred();
            int id = 0;

            id = Window.SetInterval(delegate()
            {
                console.log(string.Format("{0}: {1}", _name, _cur));
                console.log(string.Format("cur = {0}, target = {1}", _cur, _target));
                _cur++;

                if (_cur > _target)
                {
                    Window.ClearInterval(id);
                    dfd.Resolve();
                }
            }, 100);

            return dfd;
        }

    }

}
