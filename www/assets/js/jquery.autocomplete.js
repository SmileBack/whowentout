$('input.autocomplete').entwine({
  onmatch: function() {
    this.val('').attr('autocomplete', 'off');
    this._createAutocompleteList();
    this._createAutocompleteSelection();
  },
  onunmatch: function() {
    this.autocompleteList().remove();
    this.autocompleteSelection().remove();
  },
  selectedObject: function() {
    return this.selectedItem().object();
  },
  selectedItem: function() {
    return this.autocompleteSelection().getItem();
  },
  autocompleteList: function() {
    return this.data('autocompleteList');
  },
  autocompleteSelection: function() {
    return this.data('autocompleteSelection');
  },
  _createAutocompleteList: function() {
    var list = $('<ul></ul>').addClass('autocomplete_list');
    $('body').append(list);
    this.data('autocompleteList', list);
    this.autocompleteList().attachTo(this);
  },
  _createAutocompleteSelection: function() {
    var selection = $('<ul></ul>').addClass('autocomplete_selection').hide();
    selection.width(this.width());
    this.after(selection);
    this.data('autocompleteSelection', selection);
    this.autocompleteSelection().data('input', this);
  }
});

$('.autocomplete_list').entwine({
  onitemclick: function(e, item) {
    this.selectItem(item);
  },
  selectItem: function(item) {
    this.autocompleteSelection().setItem(item);
  },
  selectActiveItem: function() {
    this.selectItem(this.getActiveItem());
  },
  autocompleteSelection: function() {
    return this.input().autocompleteSelection();
  },
  input: function() {
    return this.data('input');
  },
  clearItems: function() {
    this.empty();
  },
  addItem: function(obj) {
    if (this.itemExists(obj))
      return;
    
    var li;
    if ($.isPlainObject(obj)) {
      //item is already in the list so don't add it again
      var elID = 'list_item_' + obj.id;
      li = $('<li id="' + elID + '" class="autocomplete_list_item"></li>');
      li.data('object', obj);
    }
    else if ( $(obj).is('.autocomplete_list_item') ) {
      li = $(obj);
    }
    else {
      throw new Exception('Invalid item');
    }
    
    li.itemFilter(this.itemFilter());
    
    this.append(li);
    
    return this;
  },
  addItems: function(items) {
    for (var k in items) {
      this.addItem(items[k]);
    }
    
    this.chompVisibleItems();
    this.makeFirstItemActive();
    return this;
  },
  item: function(id) {
    if ($.isPlainObject(id))
      return this.find('#list_item_' + id.id);
    else if (id instanceof $)
      return id;
  },
  getActiveItem: function() {
    return this.find('.autocomplete_list_item.active');
  },
  setActiveItem: function(item) {
    this.find('.autocomplete_list_item.active').removeClass('active');
    this.item(item).addClass('active');
    return this;
  },
  setNextActiveItem: function() {
    var nextItem = this.find('.autocomplete_list_item.active').nextAll(':visible:first');
    if (nextItem.length > 0)
      this.setActiveItem(nextItem);
    return this;
  },
  setPrevActiveItem: function() {
    var prevItem = this.find('.autocomplete_list_item.active').prevAll(':visible:first');
    if (prevItem.length > 0)
      this.setActiveItem(prevItem);
    return this;
  },
  itemExists: function(obj) {
    return this.item(obj).length > 0;
  },
  itemFilter: function(q) {
    if (q === undefined) {
      return this.data('itemFilter');
    }
    else {
      this.data('itemFilter', q);
      this.find('> li').each(function() {
        $(this).itemFilter(q);
      });
      
      this.find('> li.active').removeClass('active');
      this.find('> li:visible:first').addClass('active');
      this.chompVisibleItems();

      return this;
    }
  },
  attachTo: function(input) {
    this.data('input', $(input));
      
    this.width( this.input().outerWidth() );
    this.applyPosition(this.input(), {
      anchor: ['tc', 'bc']
    });
    
    var list = this;
    var input = list.input();
    
    function on_keydown(e) {
      if (e.keyCode == 38 || e.keyCode == 40 || e.keyCode == 13)
        return false;
    }
    
    function on_keyup(e) {
      if (e.keyCode == 38 || e.keyCode == 40 || e.keyCode == 13)
        return false;
      list.updateFromServer( $(this).val() );
      list.itemFilter( $(this).val() );
    }
    
    this.input().bind('keyup', function(e) {
      if (e.keyCode == 38) { //up
        list.setPrevActiveItem();
        return false;
      }
      else if (e.keyCode == 40) { //down
        list.setNextActiveItem();
        return false;
      }
      else if (e.keyCode == 13) { //select
        list.selectActiveItem();
        return false;
      }
    });
    this.input().bind( 'keyup', $.debounce(250, on_keyup) );
    this.input().bind( 'keydown', on_keydown);
    
    function hide_list() {
      if (input.data('keepFocus') == false)
        list.fadeOut(250);
    }
    function on_blur() {
      input.data('keepFocus', false);
      setTimeout(hide_list, 200);
    }
    function on_focus() {
      input.data('keepFocus', true);
      list.fadeIn(250);
    }
    this.input().blur(on_blur).focus(on_focus);
    
    this.itemFilter( this.input().val() );
  },
  updateFromServer: function(q) {
    var self = this;
    $.ajax({
      url: '/user/friends',
      type: 'get',
      dataType: 'json',
      data: {q: q},
      success: function(response) {
        self.addItems(response);
      }
    });
  },
  chompVisibleItems: function() {
    this.find('> li:visible:gt(5)').hide();
  },
  makeFirstItemActive: function() {
    this.setActiveItem( this.find('.autocomplete_list_item:visible:first') );
  }
});

$('.autocomplete_list_item').entwine({
  onmatch: function() {
    this.updateHTML();
  },
  onunmatch: function() {},
  onclick: function(e) {
    this.trigger('itemclick', $(this));
  },
  list: function() {
    return this.closest('.autocomplete_list');
  },
  object: function(obj) {
    if (obj === undefined) {
      return this.data('object');
    }
    else {
      this.data('object', obj);
    }
  },
  itemFilter: function(q) {
    if (q === undefined) {
      return this.data('itemFilter');
    }
    else {
      this.data('itemFilter', q);
      if (this.matches(q))
        this.show();
      else
        this.hide();
      
      return this;
    }
  },
  matches: function(q) {
    if (q == '')
      return false;
    
    var keywords = q.toLowerCase().split(/\W+/);
    var title = this.object().title.toLowerCase();
    for (var k in keywords) {
      if ( title.indexOf( keywords[k] ) == -1 )
        return false;
    }
    return true;
  },
  updateHTML: function() {
    this.empty()
        .append(this.getFacebookImage())
        .append('<span>' + this.object().title + '</span>');
        
    return this;
  },
  getFacebookImage: function() {
    return $('<img src="https://graph.facebook.com/' + this.object().id + '/picture">');
  }
});

$('.autocomplete_selection').entwine({
  setItem: function(item) {
    var item = $(item).removeClass('active').detach();
    item.append('<a class="autocomplete_close">×</a>');
    this.autocompleteList().itemFilter('');
    
    this.input().blur().hide();
    this.empty().append(item).show();
    this.input().val(this.getItem().object().id);
    
    this.input().trigger('itemselected', [item]);
  },
  autocompleteList: function() {
    return this.input().autocompleteList();
  },
  getItem: function() {
    return this.find('.autocomplete_list_item');
  },
  input: function() {
    return this.data('input');
  },
  clear: function() {
    var item = this.find('.autocomplete_list_item').detach();
    item.find('.autocomplete_close').remove();
    this.autocompleteList().addItem(item);
    this.hide();
    this.input().val('').show().focus();
  }
});

$('.autocomplete_selection .autocomplete_close').entwine({
  onclick: function() {
    this.closest('.autocomplete_selection').clear();
  }
});
