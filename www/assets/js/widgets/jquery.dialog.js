$.dialog = {
  mask: function() {
    if ( $('#mask').length == 0) {
      var mask = $('<div id="mask"/>').css({
        display: 'none',
        position: 'fixed',
        top: '0px',
        left: '0px',
        background: 'black',
        opacity: 0.4,
        width: '100%',
        height: '100%',
        'z-index': 9000
      });
      $('body').append(mask);
    }
    $('#mask').bind('click', function() {
      $('.dialog:visible').hideDialog();
    });
    return $('#mask');
  },
  create: function() {
    var d = $('<div class="dialog"> '
            + '<h1></h1>'
            + '<div class="dialog_body"></div>'
            + '<div class="dialog_buttons"></div>'
            + '</div>');
    $('body').append(d);
    return d;
  },
  buttonSets: {
    yesno: [
      {key: 'y', title: 'Yes'},
      {key: 'n', title: 'No'}
    ],
    ok: [
      {key: 'ok', title: 'OK'}
    ],
    close: [
      {key: 'close', title: 'Close'}
    ]
  }
};

$('.dialog').entwine({
  title: function(text) {
    if (text === undefined) {
      return this.find('h1').text();
    }
    else {
      this.find('h1').text(text);
      this.refreshPosition();
      return this;
    }
  },
  message: function(text) {
    if (text === undefined) {
      return this.find('.dialog_body').text();
    }
    else {
      this.find('.dialog_body').html(text);
      this.refreshPosition();
      return this;
    }
  },
  setButtons: function(buttons) {
    var self = this;
    if (typeof buttons == 'string')
      buttons = $.dialog.buttonSets[buttons];

    this.removeAllButtons();
    $.each(buttons, function(k, button) {
      self.addButton(button.key, button.title, button.properties);
    });
    
    return this;
  },
  addButton: function(key, title, attributes) {
    attributes = $.extend({}, {href: '#'}, attributes);
    var button = $('<a/>');
    button.addClass('button').html(title);
    for (var prop in attributes) {
      button.attr(prop, attributes[prop]);
    }

    button.attr('data-key', key);
    button.addClass(key);

    this.find('.dialog_buttons').append(button);

    this.refreshPosition();

    return button;
  },
  removeButton: function(key) {
    this.find('.button[data-key=' + key + ']');
    this.refreshPosition();
  },
  removeAllButtons: function() {
    this.find('.dialog_buttons').empty();
    this.refreshPosition();
  },
  showDialog: function(cls, data) {
    if (cls != null) {
      this.attr('class', 'dialog');
      this.addClass(cls);
    }
    if (data != null) {
      this.data('dialog_data', data);
    }
    $.dialog.mask().fadeIn(300);
    this.fadeIn(300);
  },
  hideDialog: function() {
    $.dialog.mask().fadeOut(300);
    this.fadeOut(300);
  }
});

$('.dialog .button').entwine({
  onclick: function(e) {
    e.preventDefault();
    
    var d = this.closest('.dialog');
    var data = d.data('dialog_data');
    d.trigger('button_click', [this, data]);

    d.hideDialog();
  }
});

$('#notice').entwine({
  showNotice: function(message, target, anchor) {
    this.empty().append(message).anchor(target, anchor).fadeIn(300);
    return this;
  },
  hideNotice: function() {
    this.fadeOut(300);
    return this;
  }
});

$.fn.notice = function(message, position) {
  position = position || 't';
  var anchors = {t: ['bc', 'tc'], b: ['tc', 'bc'], l: ['rc', 'lc'], r: ['lc', 'rc']};
  $('#notice').showNotice(message, $(this), anchors[position] || position);
  return this;
}
