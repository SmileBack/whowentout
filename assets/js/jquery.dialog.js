var dialog = Element.subclass();

function dialog_mask() {
  if ( $('#mask').length == 0) {
    $('body').append('<div id="mask" />');
  }
  $('#mask').bind('click', function() {
    dialog('.dialog:visible').hide();
  });
  return $('#mask');
}

dialog.create = function() {
  var d = dialog('<div class="dialog"> '
               + '<h1></h1>'
               + '<div class="dialog_body"></div>'
               + '<div class="dialog_buttons"></div>'
               + '</div>');
  $('body').append(d);
  return d;
}

dialog.buttonSets = {
  yesno: [
    {key: 'y', title: 'Yes'},
    {key: 'n', title: 'No'}
  ],
  ok: [
    {key: 'ok', title: 'OK'}
  ]
};

dialog.fn.setButtons = function(buttons) {
  if (typeof buttons == 'string')
    buttons = dialog.buttonSets[buttons];
  
  this.removeAllButtons();
  for (var k in buttons) {
    this.addButton(buttons[k].key, buttons[k].title, buttons[k].attributes);
  }
  return this;
}
dialog.fn.addButton = function(key, title, attributes) {
  attributes = $.extend({}, {href: '#'}, attributes);
  var button = $('<a/>');
  button.addClass('button').html(title);
  for (var prop in attributes) {
    button.attr(prop, attributes[prop]);
  }
  
  button.attr('data-key', key);
  button.addClass(key);
  
  this.find('.dialog_buttons').append(button);
  return button;
}
dialog.fn.removeButton = function(key) {
  this.find('.button[data-key=' + key + ']');
}
dialog.fn.removeAllButtons = function() {
  this.find('.dialog_buttons').empty();
}

dialog.fn.title = function(text) {
  if (text === undefined) {
    return this.find('h1').text();
  }
  else {
    this.find('h1').text(text);
    this.refreshPosition();
    return this;
  }
}
dialog.fn.message = function(text) {
  if (text === undefined) {
    return this.find('.dialog_body').text();
  }
  else {
    this.find('.dialog_body').text(text);
    this.refreshPosition();
    return this;
  }
}
dialog.fn.show = function(cls, data) {
  if (cls != null) {
    this.attr('class', 'dialog');
    this.addClass(cls);
  }
  if (data != null) {
    this.data('dialog_data', data);
  }
  dialog_mask().fadeIn(300);
  this.fadeIn(300);
}
dialog.fn.hide = function() {
  dialog_mask().fadeOut(300);
  this.fadeOut(300);
}

$('.dialog .button').live('click', function(e) {
  e.preventDefault();
  
  var button = $(this);
  var d = dialog( button.closest('.dialog') );
  var data = d.data('dialog_data');
  d.trigger('button_click', [button, data]);
  
  d.hide();
});
