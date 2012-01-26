//= require jquery.entwine.js

$('.mobile .deal_preview').entwine({
    onmatch: function() {
        this.css('cursor', 'pointer');
    },
    onunmatch: function() {
        this.css('cursor', '');
    },
    onclick: function() {
        $(this).closest('form').submit();
    }
});

$('.ticket').entwine({
    onmatch: function() {
        this.startRefreshing();
    },
    startRefreshing: function() {
        this.css({
                    position: 'fixed',
                    top: '50%',
                    left: '50%'
                });

        var ticket = this;
        var id = setInterval(function() { ticket.centerOnScreen() }, 200);
        this.entwineData('id', id);
    },
    stopRefreshing: function() {
        var id = this.entwineData('id');
        clearInterval(id);
    },
    centerOnScreen: function() {
        var width = this.outerWidth();
        var height = this.outerHeight();

        if (width == 0)
            return;

        this.css({
           marginLeft: this.outerWidth() / -2 + 'px',
           marginTop: this.outerHeight() / -2 + 'px'
        });
    }
});

function hideAddressBar()
{
  if(!window.location.hash)
  {
      if(document.height < window.outerHeight)
      {
          document.body.style.height = (window.outerHeight + 50) + 'px';
      }

      setTimeout( function(){ window.scrollTo(0, 1); }, 50 );
  }
}
window.addEventListener("load", function(){ if(!window.pageYOffset){ hideAddressBar(); } } );
window.addEventListener("orientationchange", hideAddressBar );
