$('input.autocomplete').entwine({
  onmatch: function() {
    this._createAutocompleteList();
  },
  onunmatch: function() {
    this.autocompleteList().remove();
  },
  autocompleteList: function() {
    return this.data('autocompleteList');
  },
  _createAutocompleteList: function() {
    var list = $('<ul class="autocomplete_list"></ul>');
    $('body').append(list);
    this.data('autocompleteList', list);
    this.autocompleteList().attachTo(this);
  }
});

$('.autocomplete_list').entwine({
  input: function() {
    return this.data('input');
  },
  clearItems: function() {
    this.empty();
  },
  addItem: function(obj) {
    //item is already in the list so don't add it again
    if (this.itemExists(obj))
      return;
    
    var elID = 'list_item_' + obj.id;
    var li = $('<li id="' + elID + '" class="autocomplete_list_item"/>');
    li.data('object', obj);
    
    li.itemFilter(this.itemFilter());
    
    this.append(li);
  },
  addItems: function(items) {
    for (var k in items) {
      this.addItem(items[k]);
    }
    this.chompVisibleItems();
  },
  itemExists: function(obj) {
    var elID = 'list_item_' + obj.id;
    return this.find('#' + elID).length > 0;
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
    
    var self = this;
    
    function on_keyup() {
      self.updateFromServer( $(this).val() );
      self.itemFilter( $(this).val() );
    }
    
    this.input().bind( 'keyup', $.debounce(250, on_keyup) );
    this.input().bind('focus', function() {
      self.fadeIn();
    });
    this.input().bind('blur', function() {
      self.fadeOut(250);
    });
    
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
  }
});

$('.autocomplete_list_item').entwine({
  onmatch: function() {
    this.updateHTML();
  },
  onunmatch: function() {},
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
