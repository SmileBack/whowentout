var dialog = Element.subclass();

function dialog_mask() {
  if ( $('#mask').length == 0) {
    $('body').append('<div id="mask" />');
  }
  return $('#mask');
}

dialog.create = function() {
  var d = dialog('<div class="dialog"> <h1></h1> <div class="dialog_body"></div> </div>'),
      buttons = $('<div class="dialog_buttons"> <a href="#" class="green yes button">Yes</a> <a href="#" class="red no button">No</a> </div>');
  d.append(buttons);
  $('body').append(d);
  return d;
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
