//= require jquery.js

/* jQuery.Entwine - Copyright 2009-2011 Hamish Friedlander and SilverStripe. Version . */

/* vendor/jquery.selector/jquery.class.js */

/**
 * Very basic Class utility. Based on base and jquery.class.
 *
 * Class definition: var Foo = Base.extend({ init: function(){ Constructor }; method_name: function(){ Method } });
 *
 * Inheritance: var Bar = Foo.extend({ method_name: function(){ this._super(); } });
 *
 * new-less Constructor: new Foo(arg) <-same as-> Foo(arg)
 */

var Base;

(function(){

	var marker = {}, fnTest = /xyz/.test(function(){var xyz;}) ? /\b_super\b/ : /.*/;

	// The base Class implementation (does nothing)
	Base = function(){};

	Base.addMethod = function(name, func) {
		var parent = this._super && this._super.prototype;

		if (parent && fnTest.test(func)) {
			this.prototype[name] = function(){
				var tmp = this._super;
				this._super = parent[name];
				try {
					var ret = func.apply(this, arguments);
				}
				finally {
					this._super = tmp;
				}
				return ret;
			};
		}
		else this.prototype[name] = func;
	};

	Base.addMethods = function(props) {
		for (var name in props) {
			if (typeof props[name] == 'function') this.addMethod(name, props[name]);
			else this.prototype[name] = props[name];
		}
	};

	Base.subclassOf = function(parentkls) {
		var kls = this;
		while (kls) {
			if (kls === parentkls) return true;
			kls = kls._super;
		}
	};

	// Create a new Class that inherits from this class
	Base.extend = function(props) {

		// The dummy class constructor
		var Kls = function() {
			if (arguments[0] === marker) return;

			if (this instanceof Kls) {
				if (this.init) this.init.apply(this, arguments);
			}
			else {
				var ret = new Kls(marker); if (ret.init) ret.init.apply(ret, arguments); return ret;
			}
		};

		// Add the common class variables and methods
		Kls.constructor = Kls;
		Kls.extend = Base.extend;
		Kls.addMethod = Base.addMethod;
		Kls.addMethods = Base.addMethods;
		Kls.subclassOf = Base.subclassOf;

		Kls._super = this;

		// Attach the parent object to the inheritance chain
		Kls.prototype = new this(marker);
		Kls.prototype.constructor = Kls;

		// Copy the properties over onto the new prototype
		Kls.addMethods(props);

		return Kls;
	};
})();;


/* vendor/jquery.selector/jquery.selector.js */

(function($){

	var tokens = {
		UNICODE: /\\[0-9a-f]{1,6}(?:\r\n|[ \n\r\t\f])?/,
		ESCAPE: /(?:UNICODE)|\\[^\n\r\f0-9a-f]/,
		NONASCII: /[^\x00-\x7F]/,
		NMSTART: /[_a-z]|(?:NONASCII)|(?:ESCAPE)/,
		NMCHAR: /[_a-z0-9-]|(?:NONASCII)|(?:ESCAPE)/,
		IDENT: /-?(?:NMSTART)(?:NMCHAR)*/,

		NL: /\n|\r\n|\r|\f/,

		STRING: /(?:STRING1)|(?:STRING2)|(?:STRINGBARE)/,
		STRING1: /"(?:(?:ESCAPE)|\\(?:NL)|[^\n\r\f\"])*"/,
		STRING2: /'(?:(?:ESCAPE)|\\(?:NL)|[^\n\r\f\'])*'/,
		STRINGBARE: /(?:(?:ESCAPE)|\\(?:NL)|[^\n\r\f\]])*/,

		FUNCTION: /(?:IDENT)\(\)/,

		INTEGER: /[0-9]+/,

		WITHN: /([-+])?(INTEGER)?(n)\s*(?:([-+])\s*(INTEGER))?/,
		WITHOUTN: /([-+])?(INTEGER)/
	};

	var rx = {
		not: /:not\(/,
		not_end: /\)/,

 		tag: /((?:IDENT)|\*)/,
		id: /#(IDENT)/,
		cls: /\.(IDENT)/,
		attr: /\[\s*(IDENT)\s*(?:([^=]?=)\s*(STRING)\s*)?\]/,
		pseudo_el: /(?::(first-line|first-letter|before|after))|(?:::((?:FUNCTION)|(?:IDENT)))/,
		pseudo_cls_nth: /:nth-child\(\s*(?:(?:WITHN)|(?:WITHOUTN)|(odd|even))\s*\)/,
		pseudo_cls: /:(IDENT)/,

		comb: /\s*(\+|~|>)\s*|\s+/,
		comma: /\s*,\s*/,
		important: /\s+!important\s*$/
	};

	/* Replace placeholders with actual regex, and mark all as case insensitive */
	var token = /[A-Z][A-Z0-9]+/;
	for (var k in rx) {
		var m, src = rx[k].source;
		while (m = src.match(token)) src = src.replace(m[0], tokens[m[0]].source);
		rx[k] = new RegExp(src, 'gi');
	}

	/**
	 * A string that matches itself against regexii, and keeps track of how much of itself has been matched
	 */
	var ConsumableString = Base.extend({
		init: function(str) {
			this.str = str;
			this.pos = 0;
		},
		match: function(rx) {
			var m;
			rx.lastIndex = this.pos;
			if ((m = rx.exec(this.str)) && m.index == this.pos ) {
				this.pos = rx.lastIndex ? rx.lastIndex : this.str.length ;
				return m;
			}
			return null;
		},
		peek: function(rx) {
			var m;
			rx.lastIndex = this.pos;
			if ((m = rx.exec(this.str)) && m.index == this.pos ) return m;
			return null;
		},
		showpos: function() {
			return this.str.slice(0,this.pos)+'<HERE>' + this.str.slice(this.pos);
		},
		done: function() {
			return this.pos == this.str.length;
		}
	});

	/* A base class that all Selectors inherit off */
	var SelectorBase = Base.extend({});

	/**
	 * A class representing a Simple Selector, as per the CSS3 selector spec
	 */
	var SimpleSelector = SelectorBase.extend({
		init: function() {
			this.tag = null;
			this.id = null;
			this.classes = [];
			this.attrs = [];
			this.nots = [];
			this.pseudo_classes = [];
			this.pseudo_els = [];
		},
		parse: function(selector) {
			var m;

			/* Pull out the initial tag first, if there is one */
			if (m = selector.match(rx.tag)) this.tag = m[1];

			/* Then for each selection type, try and find a match */
			do {
				if (m = selector.match(rx.not)) {
					this.nots[this.nots.length] = SelectorsGroup().parse(selector);
					if (!(m = selector.match(rx.not_end))) {
						throw 'Invalid :not term in selector';
					}
				}
				else if (m = selector.match(rx.id))         this.id = m[1];
				else if (m = selector.match(rx.cls))        this.classes[this.classes.length] = m[1];
				else if (m = selector.match(rx.attr))       this.attrs[this.attrs.length] = [ m[1], m[2], m[3] ];
				else if (m = selector.match(rx.pseudo_el))  this.pseudo_els[this.pseudo_els.length] = m[1] || m[2];
				else if (m = selector.match(rx.pseudo_cls_nth)) {
					if (m[3]) {
						var a = parseInt((m[1]||'')+(m[2]||'1'));
						var b = parseInt((m[4]||'')+(m[5]||'0'));
					}
					else {
						var a = m[8] ? 2 : 0;
						var b = m[8] ? (4-m[8].length) : parseInt((m[6]||'')+m[7]);
					}
					this.pseudo_classes[this.pseudo_classes.length] = ['nth-child', [a, b]];
				}
				else if (m = selector.match(rx.pseudo_cls)) this.pseudo_classes[this.pseudo_classes.length] = [m[1]];

			} while(m && !selector.done());

			return this;
		}
	});

	/**
	 * A class representing a Selector, as per the CSS3 selector spec
	 */
	var Selector = SelectorBase.extend({
		init: function(){
			this.parts = [];
		},
		parse: function(cons){
			this.parts[this.parts.length] = SimpleSelector().parse(cons);

			while (!cons.done() && !cons.peek(rx.comma) && (m = cons.match(rx.comb))) {
				this.parts[this.parts.length] = m[1] || ' ';
				this.parts[this.parts.length] = SimpleSelector().parse(cons);
			}

			return this.parts.length == 1 ? this.parts[0] : this;
		}
	});

	/**
	 * A class representing a sequence of selectors, as per the CSS3 selector spec
	 */
	var SelectorsGroup = SelectorBase.extend({
		init: function(){
			this.parts = [];
		},
		parse: function(cons){
			this.parts[this.parts.length] = Selector().parse(cons);

			while (!cons.done() && (m = cons.match(rx.comma))) {
				this.parts[this.parts.length] = Selector().parse(cons);
			}

			return this.parts.length == 1 ? this.parts[0] : this;
		}
	});


	$.selector = function(s){
		var cons = ConsumableString(s);
		var res = SelectorsGroup().parse(cons);

		res.selector = s;

		if (!cons.done()) throw 'Could not parse selector - ' + cons.showpos() ;
		else return res;
	};

	$.selector.SelectorBase = SelectorBase;
	$.selector.SimpleSelector = SimpleSelector;
	$.selector.Selector = Selector;
	$.selector.SelectorsGroup = SelectorsGroup;

})(jQuery);
;


/* vendor/jquery.selector/jquery.selector.specifity.js */

(function($) {

	$.selector.SimpleSelector.addMethod('specifity', function() {
		if (this.spec) return this.spec;

		var spec = [
			this.id ? 1 : 0,
			this.classes.length + this.attrs.length + this.pseudo_classes.length,
			((this.tag && this.tag != '*') ? 1 : 0) + this.pseudo_els.length
		];
		$.each(this.nots, function(i,not){
			var ns = not.specifity(); spec[0] += ns[0]; spec[1] += ns[1]; spec[2] += ns[2];
		});

		return this.spec = spec;
	});

	$.selector.Selector.addMethod('specifity', function(){
		if (this.spec) return this.spec;

		var spec = [0,0,0];
		$.each(this.parts, function(i,part){
			if (i%2) return;
			var ps = part.specifity(); spec[0] += ps[0]; spec[1] += ps[1]; spec[2] += ps[2];
		});

		return this.spec = spec;
	});

	$.selector.SelectorsGroup.addMethod('specifity', function(){
		if (this.spec) return this.spec;

		var spec = [0,0,0];
		$.each(this.parts, function(i,part){
			var ps = part.specifity(); spec[0] += ps[0]; spec[1] += ps[1]; spec[2] += ps[2];
		});

		return this.spec = spec;
	});


})(jQuery);
;


/* vendor/jquery.selector/jquery.selector.matches.js */

/*
This attempts to do the opposite of Sizzle.
Sizzle is good for finding elements for a selector, but not so good for telling if an individual element matches a selector
*/

(function($) {

	/**** CAPABILITY TESTS ****/
	var div = document.createElement('div');
	div.innerHTML = '<form id="test"><input name="id" type="text"/></form>';

	// In IE 6-7, getAttribute often does the wrong thing (returns similar to el.attr), so we need to use getAttributeNode on that browser
	var getAttributeDodgy = div.firstChild.getAttribute('id') !== 'test';

	// Does browser support Element.firstElementChild, Element.previousElementSibling, etc.
	var hasElementTraversal = div.firstElementChild && div.firstElementChild.tagName == 'FORM';

	// Does browser support Element.children
	var hasChildren = div.children && div.children[0].tagName == 'FORM';

	var FUNC_IN  = /^\s*function\s*\([^)]*\)\s*\{/;
	var FUNC_OUT = /}\s*$/;

	var funcToString = function(f) {
		return (''+f).replace(FUNC_IN,'').replace(FUNC_OUT,'');
	};

	// Can we use Function#toString ?
	try {
		var testFunc = function(){ return 'good'; };
		if ((new Function('',funcToString(testFunc)))() != 'good') funcToString = false;
	}
	catch(e) { funcToString = false; console.log(e.message);/*pass*/ }

	/**** INTRO ****/

	var GOOD = /GOOD/g;
	var BAD = /BAD/g;

	var STARTS_WITH_QUOTES = /^['"]/g;

	var join = function(js) {
		return js.join('\n');
	};

	var join_complex = function(js) {
		var code = new String(js.join('\n')); // String objects can have properties set. strings can't
		code.complex = true;
		return code;
	};

	/**** ATTRIBUTE ACCESSORS ****/

	// Not all attribute names can be used as identifiers, so we encode any non-acceptable characters as hex
	var varForAttr = function(attr) {
		return '_' + attr.replace(/^[^A-Za-z]|[^A-Za-z0-9]/g, function(m){ return '_0x' + m.charCodeAt(0).toString(16) + '_'; });
	};

	var getAttr;

	// Good browsers
	if (!getAttributeDodgy) {
		getAttr = function(attr){ return 'var '+varForAttr(attr)+' = el.getAttribute("'+attr+'");' ; };
	}
	// IE 6, 7
	else {
		// On IE 6 + 7, getAttribute still has to be called with DOM property mirror name, not attribute name. Map attributes to those names
		var getAttrIEMap = { 'class': 'className', 'for': 'htmlFor' };

		getAttr = function(attr) {
			var ieattr = getAttrIEMap[attr] || attr;
			return 'var '+varForAttr(attr)+' = el.getAttribute("'+ieattr+'",2) || (el.getAttributeNode("'+attr+'")||{}).nodeValue;';
		};
	}

	/**** ATTRIBUTE COMPARITORS ****/

	var attrchecks = {
		'-':  '!K',
		'=':  'K != "V"',
		'!=': 'K == "V"',
		'~=': '_WS_K.indexOf(" V ") == -1',
		'^=': '!K || K.indexOf("V") != 0',
		'*=': '!K || K.indexOf("V") == -1',
		'$=': '!K || K.substr(K.length-"V".length) != "V"'
	};

	/**** STATE TRACKER ****/

	var State = $.selector.State = Base.extend({
		init: function(){
			this.reset();
		},
		reset: function() {
			this.attrs = {}; this.wsattrs = {};
		},

		prev: function(){
			this.reset();
			if (hasElementTraversal) return 'el = el.previousElementSibling';
			return 'while((el = el.previousSibling) && el.nodeType != 1) {}';
		},
		next: function() {
			this.reset();
			if (hasElementTraversal) return 'el = el.nextElementSibling';
			return 'while((el = el.nextSibling) && el.nodeType != 1) {}';
		},
		prevLoop: function(body){
			this.reset();
			if (hasElementTraversal) return join([ 'while(el = el.previousElementSibling){', body]);
			return join([
				'while(el = el.previousSibling){',
					'if (el.nodeType != 1) continue;',
					body
			]);
		},
		parent: function() {
			this.reset();
			return 'el = el.parentNode;';
		},
		parentLoop: function(body) {
			this.reset();
			return join([
				'while((el = el.parentNode) && el.nodeType == 1){',
					body,
				'}'
			]);
		},

		uses_attr: function(attr) {
			if (this.attrs[attr]) return;
			this.attrs[attr] = true;
			return getAttr(attr);
		},
		uses_wsattr: function(attr) {
			if (this.wsattrs[attr]) return;
			this.wsattrs[attr] = true;
			return join([this.uses_attr(attr), 'var _WS_'+varForAttr(attr)+' = " "+'+varForAttr(attr)+'+" ";']);
		},

		save: function(lbl) {
			return 'var el'+lbl+' = el;';
		},
		restore: function(lbl) {
			this.reset();
			return 'el = el'+lbl+';';
		}
	});

	/**** PSEUDO-CLASS DETAILS ****/

	var pseudoclschecks = {
		'first-child': join([
			'var cel = el;',
			'while(cel = cel.previousSibling){ if (cel.nodeType === 1) BAD; }'
		]),
		'last-child': join([
			'var cel = el;',
			'while(cel = cel.nextSibling){ if (cel.nodeType === 1) BAD; }'
		]),
		'nth-child': function(a,b) {
			var get_i = join([
				'var i = 1, cel = el;',
				'while(cel = cel.previousSibling){',
					'if (cel.nodeType === 1) i++;',
				'}'
			]);

			if (a == 0) return join([
				get_i,
				'if (i- '+b+' != 0) BAD;'
			]);
			else if (b == 0 && a >= 0) return join([
				get_i,
				'if (i%'+a+' != 0 || i/'+a+' < 0) BAD;'
			]);
			else if (b == 0 && a < 0) return join([
				'BAD;'
			]);
			else return join([
				get_i,
				'if ((i- '+b+')%'+a+' != 0 || (i- '+b+')/'+a+' < 0) BAD;'
			]);
		}
	};

	// Needs to refence contents of object, so must be injected after definition
	pseudoclschecks['only-child'] = join([
		pseudoclschecks['first-child'],
		pseudoclschecks['last-child']
	]);

	/**** SimpleSelector ****/

	$.selector.SimpleSelector.addMethod('compile', function(el) {
		var js = [];

		/* Check against element name */
		if (this.tag && this.tag != '*') {
			js[js.length] = 'if (el.tagName != "'+this.tag.toUpperCase()+'") BAD;';
		}

		/* Check against ID */
		if (this.id) {
			js[js.length] = el.uses_attr('id');
			js[js.length] = 'if (_id !== "'+this.id+'") BAD;';
		}

		/* Build className checking variable */
		if (this.classes.length) {
			js[js.length] = el.uses_wsattr('class');

			/* Check against class names */
			$.each(this.classes, function(i, cls){
				js[js.length] = 'if (_WS__class.indexOf(" '+cls+' ") == -1) BAD;';
			});
		}

		/* Check against attributes */
		$.each(this.attrs, function(i, attr){
			js[js.length] = (attr[1] == '~=') ? el.uses_wsattr(attr[0]) : el.uses_attr(attr[0]);
			var check = attrchecks[ attr[1] || '-' ];
			check = check.replace( /K/g, varForAttr(attr[0])).replace( /V/g, attr[2] && attr[2].match(STARTS_WITH_QUOTES) ? attr[2].slice(1,-1) : attr[2] );
			js[js.length] = 'if ('+check+') BAD;';
		});

		/* Check against nots */
		$.each(this.nots, function(i, not){
			var lbl = ++lbl_id;
			var func = join([
				'l'+lbl+':{',
					not.compile(el).replace(BAD, 'break l'+lbl).replace(GOOD, 'BAD'),
				'}'
			]);

			if (!(not instanceof $.selector.SimpleSelector)) func = join([
				el.save(lbl),
				func,
				el.restore(lbl)
			]);

			js[js.length] = func;
		});

		/* Check against pseudo-classes */
		$.each(this.pseudo_classes, function(i, pscls){
			var check = pseudoclschecks[pscls[0]];
			if (check) {
				js[js.length] = ( typeof check == 'function' ? check.apply(this, pscls[1]) : check );
			}
			else if (check = $.find.selectors.filters[pscls[0]]) {
				if (funcToString) {
					js[js.length] = funcToString(check).replace(/elem/g,'el').replace(/return([^;]+);/,'if (!($1)) BAD;');
				}
				else {
					js[js.length] = 'if (!$.find.selectors.filters.'+pscls[0]+'(el)) BAD;';
				}
			}
		});

		js[js.length] = 'GOOD';

		/* Pass */
		return join(js);
	});

	var lbl_id = 0;
	/** Turns an compiled fragment into the first part of a combination */
	function as_subexpr(f) {
		if (f.complex)
			return join([
				'l'+(++lbl_id)+':{',
					f.replace(GOOD, 'break l'+lbl_id),
				'}'
			]);
		else
			return f.replace(GOOD, '');
	}

	var combines = {
		' ': function(el, f1, f2) {
			return join_complex([
				f2,
				'while(true){',
					el.parent(),
					'if (!el || el.nodeType !== 1) BAD;',
					f1.compile(el).replace(BAD, 'continue'),
				'}'
			]);
		},

		'>': function(el, f1, f2) {
			return join([
				f2,
				el.parent(),
				'if (!el || el.nodeType !== 1) BAD;',
				f1.compile(el)
			]);
		},

		'~': function(el, f1, f2) {
			return join_complex([
				f2,
				el.prevLoop(),
					f1.compile(el).replace(BAD, 'continue'),
				'}',
				'BAD;'
			]);
		},

		'+': function(el, f1, f2) {
			return join([
				f2,
				el.prev(),
				'if (!el) BAD;',
				f1.compile(el)
			]);
		}
	};

	$.selector.Selector.addMethod('compile', function(el) {
		var l = this.parts.length;

		var expr = this.parts[--l].compile(el);
		while (l) {
			var combinator = this.parts[--l];
			expr = combines[combinator](el, this.parts[--l], as_subexpr(expr));
		}

		return expr;
	});

	$.selector.SelectorsGroup.addMethod('compile', function(el) {
		var expr = [], lbl = ++lbl_id;

		for (var i=0; i < this.parts.length; i++) {
			expr[expr.length] = join([
				i == 0 ? el.save(lbl) : el.restore(lbl),
				'l'+lbl+'_'+i+':{',
					this.parts[i].compile(el).replace(BAD, 'break l'+lbl+'_'+i),
				'}'
			]);
		}

		expr[expr.length] = 'BAD;';
		return join(expr);
	});

	$.selector.SelectorBase.addMethod('matches', function(el){
		this.matches = new Function('el', join([
			'if (!el) return false;',
			this.compile(new State()).replace(BAD, 'return false').replace(GOOD, 'return true')
		]));
		return this.matches(el);
	});

})(jQuery);

;


/* src/jquery.focusinout.js */

(function($){

	/**
	 * Add focusin and focusout support to bind and live for browers other than IE. Designed to be usable in a delegated fashion (like $.live)
	 * Copyright (c) 2007 Jörn Zaefferer
	 */
	$.support.focusInOut = !!($.browser.msie);
	if (!$.support.focusInOut) {
		// Emulate focusin and focusout by binding focus and blur in capturing mode
		$.each({focus: 'focusin', blur: 'focusout'}, function(original, fix){
			$.event.special[fix] = {
				setup: function(){
					if (!this.addEventListener) return false;
					this.addEventListener(original, $.event.special[fix].handler, true);
				},
				teardown: function(){
					if (!this.removeEventListener) return false;
					this.removeEventListener(original, $.event.special[fix].handler, true);
				},
				handler: function(e){
					arguments[0] = $.event.fix(e);
					arguments[0].type = fix;
					return $.event.handle.apply(this, arguments);
				}
			};
		});
	}

	(function(){
		//IE has some trouble with focusout with select and keyboard navigation
		var activeFocus = null;

		$(document)
			.bind('focusin', function(e){
				var target = e.realTarget || e.target;
				if (activeFocus && activeFocus !== target) {
					e.type = 'focusout';
					$(activeFocus).trigger(e);
					e.type = 'focusin';
					e.target = target;
				}
				activeFocus = target;
			})
			.bind('focusout', function(e){
				activeFocus = null;
			});
	})();

})(jQuery);;


/* src/jquery.entwine.js */

try {
	console.log;
}
catch (e) {
	window.console = undefined;
}

(function($) {

	var namespaces = {};

	$.entwine = function() {
		$.fn.entwine.apply(null, arguments);
	};

	/**
	 * A couple of utility functions for accessing the store outside of this closure, and for making things
	 * operate in a little more easy-to-test manner
	 */
	$.extend($.entwine, {
		/**
		 * Get all the namespaces. Useful for introspection? Internal interface of Namespace not guaranteed consistant
		 */
		namespaces: namespaces,

		/**
		 * Remove all entwine rules
		 */
		clear_all_rules: function() {
			// Remove proxy functions
			for (var k in $.fn) { if ($.fn[k].isentwinemethod) delete $.fn[k]; }
			// Remove bound events - TODO: Make this pluggable, so this code can be moved to jquery.entwine.events.js
			$(document).unbind('.entwine');
			// Remove namespaces, and start over again
			namespaces = $.entwine.namespaces = {};
		},

		WARN_LEVEL_NONE: 0,
		WARN_LEVEL_IMPORTANT: 1,
		WARN_LEVEL_BESTPRACTISE: 2,

		/**
		 * Warning level. Set to a higher level to get warnings dumped to console.
		 */
		warningLevel: 0,

		/** Utility to optionally display warning messages depending on level */
		warn: function(message, level) {
			if (level <= $.entwine.warningLevel && console && console.warn) {
				console.warn(message);
				if (console.trace) console.trace();
			}
		},

		warn_exception: function(where, /* optional: */ on, e) {
			if ($.entwine.WARN_LEVEL_IMPORTANT <= $.entwine.warningLevel && console && console.warn) {
				if (arguments.length == 2) { e = on; on = null; }

				if (on) console.warn('Uncaught exception',e,'in',where,'on',on);
				else    console.warn('Uncaught exception',e,'in',where);

				if (e.stack) console.warn("Stack Trace:\n" + e.stack);
			}
		}
	});


	/** Stores a count of definitions, so that we can sort identical selectors by definition order */
	var rulecount = 0;

	var Rule = Base.extend({
		init: function(selector, name) {
			this.selector = selector;
			this.specifity = selector.specifity();
			this.important = 0;
			this.name = name;
			this.rulecount = rulecount++;
		}
	});

	Rule.compare = function(a, b) {
		var as = a.specifity, bs = b.specifity;

		return (a.important - b.important) ||
		       (as[0] - bs[0]) ||
		       (as[1] - bs[1]) ||
		       (as[2] - bs[2]) ||
		       (a.rulecount - b.rulecount) ;
	};

	$.entwine.RuleList = function() {
		var list = [];

		list.addRule = function(selector, name){
			var rule = Rule(selector, name);

			list[list.length] = rule;
			list.sort(Rule.compare);

			return rule;
		};

		return list;
	};

	var handlers = [];

	/**
	 * A Namespace holds all the information needed for adding entwine methods to a namespace (including the _null_ namespace)
	 */
	$.entwine.Namespace = Base.extend({
		init: function(name){
			if (name && !name.match(/^[A-Za-z0-9.]+$/)) $.entwine.warn('Entwine namespace '+name+' is not formatted as period seperated identifiers', $.entwine.WARN_LEVEL_BESTPRACTISE);
			name = name || '__base';

			this.name = name;
			this.store = {};

			namespaces[name] = this;

			if (name == "__base") {
				this.injectee = $.fn;
				this.$ = $;
			}
			else {
				// We're in a namespace, so we build a Class that subclasses the jQuery Object Class to inject namespace functions into

				// jQuery 1.5 already provides a nice way to subclass, so use it
				if ($.sub) {
					this.$ = $.sub();
					this.injectee = this.$.prototype;
				}
				// For jQuery < 1.5 we have to do it ourselves
				else {
					var subfn = function(){};
					this.injectee = subfn.prototype = new $;

					// And then we provide an overriding $ that returns objects of our new Class, and an overriding pushStack to catch further selection building
					var bound$ = this.$ = function(a) {
						// Try the simple way first
						var jq = $.fn.init.apply(new subfn(), arguments);
						if (jq instanceof subfn) return jq;

						// That didn't return a bound object, so now we need to copy it
						var rv = new subfn();
						rv.selector = jq.selector; rv.context = jq.context; var i = rv.length = jq.length;
						while (i--) rv[i] = jq[i];
						return rv;
					};

					this.injectee.pushStack = function(elems, name, selector){
						var ret = bound$(elems);

						// Add the old object onto the stack (as a reference)
						ret.prevObject = this;
						ret.context = this.context;

						if ( name === "find" ) ret.selector = this.selector + (this.selector ? " " : "") + selector;
						else if ( name )       ret.selector = this.selector + "." + name + "(" + selector + ")";

						// Return the newly-formed element set
						return ret;
					};

					// Copy static functions through from $ to this.$ so e.g. $.ajax still works
					// @bug, @cantfix: Any class functions added to $ after this call won't get mirrored through
					$.extend(this.$, $);
				}

				// We override entwine to inject the name of this namespace when defining blocks inside this namespace
				var entwine_wrapper = this.injectee.entwine = function(spacename) {
					var args = arguments;

					if (!spacename || typeof spacename != 'string') { args = $.makeArray(args); args.unshift(name); }
					else if (spacename.charAt(0) != '.') args[0] = name+'.'+spacename;

					return $.fn.entwine.apply(this, args);
				};

				this.$.entwine = function() {
					entwine_wrapper.apply(null, arguments);
				};

				for (var i = 0; i < handlers.length; i++) {
					var handler = handlers[i], builder;

					// Inject jQuery object method overrides
					if (builder = handler.namespaceMethodOverrides) {
						var overrides = builder(this);
						for (var k in overrides) this.injectee[k] = overrides[k];
					}

					// Inject $.entwine function overrides
					if (builder = handler.namespaceStaticOverrides) {
						var overrides = builder(this);
						for (var k in overrides) this.$.entwine[k] = overrides[k];
					}
				}
			}
		},

		/**
		 * Returns a function that does selector matching against the function list for a function name
		 * Used by proxy for all calls, and by ctorProxy to handle _super calls
		 * @param {String} name - name of the function as passed in the construction object
		 * @param {String} funcprop - the property on the Rule object that gives the actual function to call
		 * @param {function} basefunc - the non-entwine function to use as the catch-all function at the bottom of the stack
		 */
		one: function(name, funcprop, basefunc) {
			var namespace = this;
			var funcs = this.store[name];

			var one = function(el, args, i){
				if (i === undefined) i = funcs.length;
				while (i--) {
					if (funcs[i].selector.matches(el)) {
						var ret, tmp_i = el.i, tmp_f = el.f;
						el.i = i; el.f = one;
						try { ret = funcs[i][funcprop].apply(namespace.$(el), args); }
						finally { el.i = tmp_i; el.f = tmp_f; }
						return ret;
					}
				}
				// If we didn't find a entwine-defined function, but there is a non-entwine function to use as a base, try that
				if (basefunc) return basefunc.apply(namespace.$(el), args);
			};

			return one;
		},

		/**
		 * A proxy is a function attached to a callable object (either the base jQuery.fn or a subspace object) which handles
		 * finding and calling the correct function for each member of the current jQuery context
		 * @param {String} name - name of the function as passed in the construction object
		 * @param {function} basefunc - the non-entwine function to use as the catch-all function at the bottom of the stack
		 */
		build_proxy: function(name, basefunc) {
			var one = this.one(name, 'func', basefunc);

			var prxy = function() {
				var rv, ctx = $(this);

				var i = ctx.length;
				while (i--) rv = one(ctx[i], arguments);
				return rv;
			};

			return prxy;
		},

		bind_proxy: function(selector, name, func) {
			var rulelist = this.store[name] || (this.store[name] = $.entwine.RuleList());

			var rule = rulelist.addRule(selector, name); rule.func = func;

			if (!this.injectee.hasOwnProperty(name) || !this.injectee[name].isentwinemethod) {
				this.injectee[name] = this.build_proxy(name, this.injectee.hasOwnProperty(name) ? this.injectee[name] : null);
				this.injectee[name].isentwinemethod = true;
			}

			if (!this.injectee[name].isentwinemethod) {
				$.entwine.warn('Warning: Entwine function '+name+' clashes with regular jQuery function - entwine function will not be callable directly on jQuery object', $.entwine.WARN_LEVEL_IMPORTANT);
			}
		},

		add: function(selector, data) {
			// For every item in the hash, try ever method handler, until one returns true
			for (var k in data) {
				var v = data[k];

				for (var i = 0; i < handlers.length; i++) {
					if (handlers[i].bind && handlers[i].bind.call(this, selector, k, v)) break;
				}
			}
		},

		has: function(ctx, name) {
			var rulelist = this.store[name];
			if (!rulelist) return false;

			/* We go forward this time, since low specifity is likely to knock out a bunch of elements quickly */
			for (var i = 0 ; i < rulelist.length; i++) {
				ctx = ctx.not(rulelist[i].selector);
				if (!ctx.length) return true;
			}
			return false;
		}
	});

	/**
	 * A handler is some javascript code that adds support for some time of key / value pair passed in the hash to the Namespace add method.
	 * The default handlers provided (and included by default) are event, ctor and properties
	 */
	$.entwine.Namespace.addHandler = function(handler) {
		for (var i = 0; i < handlers.length && handlers[i].order < handler.order; i++) { /* Pass */ }
		handlers.splice(i, 0, handler);
	};

	$.entwine.Namespace.addHandler({
		order: 50,

		bind: function(selector, k, v){
			if ($.isFunction(v)) {
				this.bind_proxy(selector, k, v);
				return true;
			}
		}
	});

	$.extend($.fn, {
		/**
		 * Main entwine function. Used for new definitions, calling into a namespace (or forcing the base namespace) and entering a using block
		 *
		 */
		entwine: function(spacename) {
			var i = 0;
			/* Don't actually work out selector until we try and define something on it - we might be opening a namespace on an function-traveresed object
			   which have non-standard selectors like .parents(.foo).slice(0,1) */
			var selector = null;

			/* By default we operator on the base namespace */
			var namespace = namespaces.__base || $.entwine.Namespace();

			/* If the first argument is a string, then it's the name of a namespace. Look it up */
			if (typeof spacename == 'string') {
				if (spacename.charAt('0') == '.') spacename = spacename.substr(1);
				if (spacename) namespace = namespaces[spacename] || $.entwine.Namespace(spacename);
				i=1;
			}

			/* All remaining arguments should either be using blocks or definition hashs */
			while (i < arguments.length) {
				var res = arguments[i++];

				// If it's a function, call it - either it's a using block or it's a namespaced entwine definition
				if ($.isFunction(res)) {
					if (res.length != 1) $.entwine.warn('Function block inside entwine definition does not take $ argument properly', $.entwine.WARN_LEVEL_IMPORTANT);
					res = res.call(namespace.$(this), namespace.$);
				}

				// If we have a entwine definition hash, inject it into namespace
				if (res) {
					if (selector === null) selector = this.selector ? $.selector(this.selector) : false;

					if (selector) namespace.add(selector, res);
					else $.entwine.warn('Entwine block given to entwine call without selector. Make sure you call $(selector).entwine when defining blocks', $.entwine.WARN_LEVEL_IMPORTANT);
				}
			}

			/* Finally, return the jQuery object 'this' refers to, wrapped in the new namespace */
			return namespace.$(this);
		},

		/**
		 * Calls the next most specific version of the current entwine method
		 */
		_super: function(){
			var rv, i = this.length;
			while (i--) {
				var el = this[0];
				rv = el.f(el, arguments, el.i);
			}
			return rv;
		}
	});

})(jQuery);
;


/* src/jquery.entwine.dommaybechanged.js */

(function($){

	/** What to call to run a function 'soon'. Normally setTimeout, but for syncronous mode we override so soon === now */
	var runSoon = window.setTimeout;

	/** The timer handle for the asyncronous matching call */
	var check_id = null;

	/** Fire the change event. Only fires on the document node, so bind to that */
	var triggerEvent = function() {
		$(document).triggerHandler('DOMMaybeChanged');
		check_id = null;
	};

	$.extend($.entwine, {
		/**
		 * Make onmatch and onunmatch work in synchronous mode - that is, new elements will be detected immediately after
		 * the DOM manipulation that made them match. This is only really useful for during testing, since it's pretty slow
		 * (otherwise we'd make it the default).
		 */
		synchronous_mode: function() {
			if (check_id) clearTimeout(check_id); check_id = null;
			runSoon = function(func, delay){ func.call(this); return null; };
		},

		/**
		 * Trigger onmatch and onunmatch now - usefull for after DOM manipulation by methods other than through jQuery.
		 * Called automatically on document.ready
		 */
		triggerMatching: function() {
			matching();
		}
	});

	function registerMutateFunction() {
		$.each(arguments, function(i,func){
			var old = $.fn[func];
			$.fn[func] = function() {
				var rv = old.apply(this, arguments);
				if (!check_id) check_id = runSoon(triggerEvent, 100);
				return rv;
			};
		});
	}

	function registerSetterGetterFunction() {
		$.each(arguments, function(i,func){
			var old = $.fn[func];
			$.fn[func] = function(a, b) {
				var rv = old.apply(this, arguments);
				if (!check_id && (b !== undefined || typeof a != 'string')) check_id = runSoon(triggerEvent, 100);
				return rv;
			};
		});
	}

	// Register core DOM manipulation methods
	registerMutateFunction('html', 'append', 'prepend', 'after', 'before', 'wrap', 'removeAttr', 'addClass', 'removeClass', 'toggleClass', 'empty', 'remove');
	registerSetterGetterFunction('attr', 'prop');

	// And on DOM ready, trigger matching once
	$(function(){ triggerEvent(); });

})(jQuery);;


/* src/jquery.entwine.events.js */

(function($) {

	/* Add the methods to handle event binding to the Namespace class */
	$.entwine.Namespace.addMethods({

        bind_event: function(selector, name, func, event) {
            $(selector.selector).live(event, function() {
                var el = $(this);
                func.apply(el, arguments);
            });
        }

	});

	$.entwine.Namespace.addHandler({
		order: 40,

		bind: function(selector, k, v){
			var match, event;
			if ($.isFunction(v) && (match = k.match(/^on(.*)/))) {
				event = match[1];
				this.bind_event(selector, k, v, event);
				return true;
			}
		}
	});

})(jQuery);
	;


/* src/jquery.entwine.ctors.js */

(function($) {

	/* Add the methods to handle constructor & destructor binding to the Namespace class */
	$.entwine.Namespace.addMethods({
		bind_condesc: function(selector, name, func) {
			var ctors = this.store.ctors || (this.store.ctors = $.entwine.RuleList()) ;

			var rule;
			for (var i = 0 ; i < ctors.length; i++) {
				if (ctors[i].selector.selector == selector.selector) {
					rule = ctors[i]; break;
				}
			}
			if (!rule) {
				rule = ctors.addRule(selector, 'ctors');
			}

			rule[name] = func;

			if (!ctors[name+'proxy']) {
				var one = this.one('ctors', name);
				var namespace = this;

				var proxy = function(els, i, func) {
					var j = els.length;
					while (j--) {
						var el = els[j];

						var tmp_i = el.i, tmp_f = el.f;
						el.i = i; el.f = one;

						try      { func.call(namespace.$(el)); }
						catch(e) { $.entwine.warn_exception(name, el, e); }
						finally  { el.i = tmp_i; el.f = tmp_f; }
					}
				};

				ctors[name+'proxy'] = proxy;
			}
		}
	});

	$.entwine.Namespace.addHandler({
		order: 30,

		bind: function(selector, k, v) {
			if ($.isFunction(v) && (k == 'onmatch' || k == 'onunmatch')) {
				this.bind_condesc(selector, k, v);
				return true;
			}
		}
	});

	/**
	 * Finds all the elements that now match a different rule (or have been removed) and call onmatch on onunmatch as appropriate
	 *
	 * Because this has to scan the DOM, and is therefore fairly slow, this is normally triggered off a short timeout, so that
	 * a series of DOM manipulations will only trigger this once.
	 *
	 * The downside of this is that things like:
	 *   $('#foo').addClass('tabs'); $('#foo').tabFunctionBar();
	 * won't work.
	 */
	$(document).bind('DOMMaybeChanged', function(){
		// For every namespace
		for (var k in $.entwine.namespaces) {
			// That has constructors or destructors
			var ctors = $.entwine.namespaces[k].store.ctors;
			if (ctors) {

				// Keep a record of elements that have matched already
				var matched = $([]), add, rem, res, rule, sel, ctor, dtor;
				// Stepping through each selector from most to least specific
				var j = ctors.length;
				while (j--) {
					// Build some quick-access variables
					rule = ctors[j];
					sel = rule.selector.selector;
					ctor = rule.onmatch;
					dtor = rule.onunmatch;

					// Get the list of elements that match this selector, that haven't yet matched a more specific selector
					res = add = $(sel).not(matched);

					// If this selector has a list of elements it matched against last time
					if (rule.cache) {
						// Find the ones that are extra this time
						add = res.not(rule.cache);
						if (dtor) {
							// Find the ones that are gone this time
							rem = rule.cache.not(res);
							// And call the destructor on them
							if (rem.length) ctors.onunmatchproxy(rem, j, dtor);
						}
					}

					// Call the constructor on the newly matched ones
					if (add.length && ctor) ctors.onmatchproxy(add, j, ctor);

					// Add these matched ones to the list tracking all elements matched so far
					matched = matched.add(res);
					// And remember this list of matching elements again this selector, so next matching we can find the unmatched ones
					ctors[j].cache = res;
				}
			}
		}
	});


})(jQuery);
;


/* src/jquery.entwine.properties.js */

(function($) {

	var entwine_prepend = '__entwine!';

	var getEntwineData = function(el, namespace, property) {
		return el.data(entwine_prepend + namespace + '!' + property);
	};

	var setEntwineData = function(el, namespace, property, value) {
		return el.data(entwine_prepend + namespace + '!' + property, value);
	};

	var getEntwineDataAsHash = function(el, namespace) {
		var hash = {};
		var id = jQuery.data(el[0]);

		var matchstr = entwine_prepend + namespace + '!';
		var matchlen = matchstr.length;

		var cache = jQuery.cache[id];
		for (var k in cache) {
			if (k.substr(0,matchlen) == matchstr) hash[k.substr(matchlen)] = cache[k];
		}

		return hash;
	};

	var setEntwineDataFromHash = function(el, namespace, hash) {
		for (var k in hash) setEntwineData(namespace, k, hash[k]);
	};

	var entwineData = function(el, namespace, args) {
		switch (args.length) {
			case 0:
				return getEntwineDataAsHash(el, namespace);
			case 1:
				if (typeof args[0] == 'string') return getEntwineData(el, namespace, args[0]);
				else                            return setEntwineDataFromHash(el, namespace, args[0]);
			default:
				return setEntwineData(el, namespace, args[0], args[1]);
		}
	};

	$.extend($.fn, {
		entwineData: function() {
			return entwineData(this, '__base', arguments);
		}
	});

	$.entwine.Namespace.addHandler({
		order: 60,

		bind: function(selector, k, v) {
			if (k.charAt(0) != k.charAt(0).toUpperCase()) $.entwine.warn('Entwine property '+k+' does not start with a capital letter', $.entwine.WARN_LEVEL_BESTPRACTISE);

			// Create the getters and setters

			var getterName = 'get'+k;
			var setterName = 'set'+k;

			this.bind_proxy(selector, getterName, function() { var r = this.entwineData(k); return r === undefined ? v : r; });
			this.bind_proxy(selector, setterName, function(v){ return this.entwineData(k, v); });

			// Get the get and set proxies we just created

			var getter = this.injectee[getterName];
			var setter = this.injectee[setterName];

			// And bind in the jQuery-style accessor

			this.bind_proxy(selector, k, function(v){ return (arguments.length == 1 ? setter : getter).call(this, v) ; });

			return true;
		},

		namespaceMethodOverrides: function(namespace){
			return {
				entwineData: function() {
					return entwineData(this, namespace.name, arguments);
				}
			};
		}
	});

})(jQuery);
;


/* src/jquery.entwine.legacy.js */

(function($) {

	// Adds back concrete methods for backwards compatibility
	$.concrete = $.entwine;
	$.fn.concrete = $.fn.entwine;
	$.fn.concreteData = $.fn.entwineData;

	// Use addHandler to hack in the namespace.$.concrete equivilent to the namespace.$.entwine namespace-injection
	$.entwine.Namespace.addHandler({
		order: 100,
		bind: function(selector, k, v) { return false; },

		namespaceMethodOverrides: function(namespace){
			namespace.$.concrete = namespace.$.entwine;
			namespace.injectee.concrete = namespace.injectee.entwine;
			namespace.injectee.concreteData = namespace.injectee.entwineData;
			return {};
		}
	});

})(jQuery);
;
