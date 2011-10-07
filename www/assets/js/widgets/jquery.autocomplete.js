//= require lib/jquery.js
//= require lib/jquery.entwine.js
//= require time.js

$('input.autocomplete').entwine({
    onmatch: function() {
        this._super();
        this.val('').attr('autocomplete', 'off');
        this._createAutocompleteList();
        this._createAutocompleteSelection();
    },
    onunmatch: function() {
        this._super();
        this.autocompleteList().remove();
        this.autocompleteSelection().remove();
    },
    source: function() {
        return this.attr('source');
    },
    selectedObject: function() {
        return this.selectedItem().object();
    },
    selectedItem: function() {
        return this.autocompleteSelection().getItem();
    },
    selectItem: function(item) {
        this.autocompleteSelection().setItem(item);
    },
    getActiveItem: function() {
        return this.autocompleteList().getActiveItem();
    },
    selectActiveItem: function() {
        this.autocompleteList().selectActiveItem();
    },
    autocompleteList: function() {
        return this.data('autocompleteList');
    },
    autocompleteSelection: function() {
        return this.data('autocompleteSelection');
    },
    matchingItems: function() {
        return this.autocompleteList().matchingItems();
    },
    _createAutocompleteList: function() {
        var extraClass = this.attr('extra_class');
        var list = $('<ul></ul>').addClass('autocomplete_list').addClass(extraClass);
        $('body').append(list);
        this.data('autocompleteList', list);
        this.autocompleteList().attachTo(this);
    },
    _createAutocompleteSelection: function() {
        var extraClass = this.attr('extra_class');
        var selection = $('<ul></ul>').addClass('autocomplete_selection').addClass(extraClass).hide();
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
    matchingItems: function() {
        return this.find('.match');
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
        else if ($(obj).is('.autocomplete_list_item')) {
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
        for (var k = 0; k < items.length; k++) {
            this.addItem(items[k]);
        }

        this.truncateVisibleItems();
        this.sortVisibleItems();
        this.makeFirstItemActive();

        return this;
    },
    item: function(id) {
        if ($.isPlainObject(id))
            return this.find('#list_item_' + id.id);
        else if (id instanceof $)
            return this.find(id);
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

            this.truncateVisibleItems();
            this.sortVisibleItems();
            this.makeFirstItemActive();

            return this;
        }
    },
    attachTo: function(input) {
        this.data('input', $(input));

        this.width(this.input().outerWidth());
        this.anchor(this.input(), ['tc', 'bc']);

        var list = this;
        var input = list.input();

        function on_keydown(e) {
            if (e.keyCode == 38 || e.keyCode == 40 || e.keyCode == 13)
                return false;
        }

        function on_keyup(e) {
            if (e.keyCode == 38 || e.keyCode == 40 || e.keyCode == 13)
                return false;
            list.updateFromServer($(this).val());
            list.itemFilter($(this).val());
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
        this.input().bind('keyup', _.debounce(on_keyup, 250));
        this.input().bind('keydown', on_keydown);

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
            list.refreshPosition().fadeIn(function() {
                list.itemFilter(input.val());
            });
        }

        this.input().blur(on_blur).focus(on_focus);

        this.itemFilter(this.input().val());
    },
    source: function() {
        return this.input().source();
    },
    updateFromServer: function(q) {
        var self = this;
        $.ajax({
            url: this.source(),
            type: 'get',
            dataType: 'json',
            data: {q: q},
            success: function(response) {
                self.addItems(response);
            }
        });
    },
    truncateVisibleItems: function() {
        this.find('> li:visible:gt(5)').hide();
    },
    sortVisibleItems: function() {
        var visibleItems = [];
        this.find('> li:visible').each(function() {
            visibleItems.push($(this));
        });
        visibleItems.sort(function(a, b) {
            return a.object().title.localeCompare(
            b.object().title
            );
        });
        visibleItems.reverse();
        for (var k = 0; k < visibleItems.length; k++) {
            visibleItems[k].detach().prependTo(this);
        }
    },
    makeFirstItemActive: function() {
        this.setActiveItem(this.find('.autocomplete_list_item:visible:first'));
    }
});

$('.autocomplete_list_item').entwine({
    onmatch: function() {
        this.updateHTML();
    },
    onunmatch: function() {
    },
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
                this.addClass('match').show();
            else
                this.removeClass('match').hide();

            return this;
        }
    },
    matches: function(q) {
        if (q == '')
            return false;

        var keywords = q.split(/\W+/);
        var title = this.object().title;
        var re;
        for (var k = 0; k < keywords.length; k++) {
            re = new RegExp('\\b' + keywords[k], 'gi');
            if (title.match(re) == null)
                return false;
        }
        return true;
    },
    updateHTML: function() {
        this.empty()
        .append('<span>' + this.object().title + '</span>');
        return this;
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

        var event = $.Event('itemselected', { item: item, object: item.object() });
        this.input().trigger(event);
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
        this.input().val('').show();

        var event = $.Event('itemdeselected', { item: item, object: item.object() });
        this.input().trigger(event);
    }
});

$('.autocomplete_selection .autocomplete_list_item').entwine({
    onclick: function() {
        var title = this.object().title;
        var selection = this.closest('.autocomplete_selection');
        selection.clear();
        selection.input().val(title).focus();
    }
});

$('.autocomplete_selection .autocomplete_close').entwine({
    onclick: function() {
        this.closest('.autocomplete_selection').clear();
    }
});
