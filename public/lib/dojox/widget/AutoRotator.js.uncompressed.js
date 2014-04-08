require({cache:{
'dojox/widget/rotator/Controller':function(){
define([
	"dojo/_base/declare",
	"dojo/_base/lang",
	"dojo/_base/html",
	"dojo/_base/event",
	"dojo/_base/array",
	"dojo/_base/connect",
	"dojo/query"
], function(declare, lang, html, event, array, connect, query) {

	var _dojoxRotator = "dojoxRotator",
		_play = _dojoxRotator + "Play",
		_pause = _dojoxRotator + "Pause",
		_number = _dojoxRotator + "Number",
		_tab = _dojoxRotator + "Tab",
		_selected = _dojoxRotator + "Selected";

	return declare("dojox.widget.rotator.Controller", null, {
		// summary:
		//		A controller that manipulates a Rotator or AutoRotator.
		// description:
		//		Displays a series of controls that send actions to a Rotator or
		//		AutoRotator.  The Controller supports the following controls:
		//
		//		- Next pane
		//		- Previous pane
		//		- Play/Pause toggler
		//		- Numbered tabs
		//		- Titled tabs
		//		- Information
		//
		//		You may specify any of these controls in any order.  You may also
		//		have multiple Controllers tied to a single Rotator instance.
		//
		//		The Controller's DOM node may also be styled for positioning or
		//		other styled preferences.
		// example:
		//	|	<div dojoType="dojox.widget.rotator.Controller"
		//	|		rotator="myRotator"
		//	|	></div>
		// example:
		//	|	<div dojoType="dojox.widget.rotator.Controller"
		//	|		rotator="myRotator"
		//	|		controls="prev,#,next"
		//	|		class="myCtrl"
		//	|	></div>
		// example:
		//	|	<div dojoType="dojox.widget.rotator.Controller"
		//	|		rotator="myRotator"
		//	|		controls="titles"
		//	|		class="myCtrl"
		//	|	></div>s

		// rotator: dojox.widget.Rotator
		//		An instance of a Rotator widget.
		rotator: null,

		// commands: string
		//		A comma-separated list of commands. Valid commands are:
		//		  prev			An icon button to go to the previous pane.
		//		  next			An icon button to go to the next pane.
		//		  play/pause	A play and pause toggle icon button.
		//		  info			Displays the current and total panes. (ie "1 / 4")
		//		  #				Displays a number button for each pane. (ie "1 2 3 4")
		//		  titles		Displays each pane's title as a tab. (ie "Home Services Contact Blog")
		commands: "prev,play/pause,info,next",

		constructor: function(/*Object*/params, /*DomNode|string*/node){
			// summary:
			//		Initializes the pager and connect to the rotator.

			lang.mixin(this, params);

			// check if we have a valid rotator
			var r = this.rotator;
			if(r){
				// remove all of the controller's child nodes just in case
				while(node.firstChild){
					node.removeChild(node.firstChild);
				}

				var ul = this._domNode = html.create("ul", null, node),
					icon = " " + _dojoxRotator + "Icon",

					// helper function for creating a button
					cb = function(/*string*/label, /*string*/css, /*array*/action){
						html.create("li", {
							className: css,
							innerHTML: '<a href="#"><span>' + label + '</span></a>',
							onclick: function(/*event*/e){
								event.stop(e);
								if(r){
									r.control.apply(r, action);
								}
							}
						}, ul);
					};

				// build out the commands
				array.forEach(this.commands.split(','), function(b, i){
					switch(b){
						case "prev":
							cb("Prev", _dojoxRotator + "Prev" + icon, ["prev"]);
							break;
						case "play/pause":
							cb("Play", _play + icon, ["play"]);
							cb("Pause", _pause + icon, ["pause"]);
							break;
						case "info":
							this._info = html.create("li", {
								className: _dojoxRotator + "Info",
								innerHTML: this._buildInfo(r)
							}, ul);
							break;
						case "next":
							cb("Next", _dojoxRotator + "Next" + icon, ["next"]);
							break;
						case "#":
						case "titles":
							for(var j=0; j<r.panes.length; j++){
								cb(
									/*label*/ b == '#' ? j+1 : r.panes[j].title || "Tab " + (j+1),
									/*css*/ (b == '#' ? _number : _tab) + ' ' + (j == r.idx ? _selected : "") + ' ' + _dojoxRotator + "Pane" + j,
									/*action*/ ["go", j]
								);
							}
							break;
					}
				}, this);

				// add the first/last classes for styling
				query("li:first-child", ul).addClass(_dojoxRotator + "First");
				query("li:last-child", ul).addClass(_dojoxRotator + "Last");

				// set the initial state of the play/pause toggle button
				this._togglePlay();

				this._con = connect.connect(r, "onUpdate", this, "_onUpdate");
			}
		},

		destroy: function(){
			// summary:
			//		Disconnect from the rotator.
			connect.disconnect(this._con);
			html.destroy(this._domNode);
		},

		_togglePlay: function(/*boolean*/playing){
			// summary:
			//		Toggles the play/pause button, if it exists.

			var p = this.rotator.playing;
			query('.'+_play, this._domNode).style("display", p ? "none" : "");
			query('.'+_pause, this._domNode).style("display", p ? "" : "none");
		},

		_buildInfo: function(/*dojox.widget.Rotator*/r){
			// summary:
			//		Return a string containing the current pane number and the total number of panes.
			return '<span>' + (r.idx+1) + ' / ' + r.panes.length + '</span>'; /*string*/
		},

		_onUpdate: function(/*string*/type){
			// summary:
			//		Updates various pager controls when the rotator updates.

			var r = this.rotator; // no need to test if this is null since _onUpdate is only fired by the rotator

			switch(type){
				case "play":
				case "pause":
					this._togglePlay();
					break;
				case "onAfterTransition":
					if(this._info){
						this._info.innerHTML = this._buildInfo(r);
					}

					// helper function for selecting the current tab
					var s = function(/*NodeList*/n){
						if(r.idx < n.length){
							html.addClass(n[r.idx], _selected);
						}
					};

					s(query('.' + _number, this._domNode).removeClass(_selected));
					s(query('.' + _tab, this._domNode).removeClass(_selected));
					break;
			}
		}
	});
});
},
'dojox/widget/rotator/ThumbnailController':function(){
define([
	"dojo/_base/declare",
	"dojo/_base/connect",
	"dojo/_base/lang",
	"dojo/_base/event",
	"dojo/aspect",
	"dojo/dom-attr",
	"dojo/dom-class",
	"dojo/dom-construct",
	"dojo/query"
], function(declare, connect, lang, event, aspect, domAttr, domClass, domConstruct, query) {

	var _css = "dojoxRotatorThumb",
		_selected = _css + "Selected";

	return declare("dojox.widget.rotator.ThumbnailController", null, {
		// summary:
		//		A rotator controller that displays thumbnails of each rotator pane.
		// description:
		//		The ThumbnailController will look at each of the rotator's panes and
		//		only if the node is an `<img>` tag, then it will create an thumbnail of
		//		the pane's image using the `<img>` tag's "thumbsrc" or "src" attribute.
		//
		//		The size of the thumbnails and the style of the selected thumbnail is
		//		controlled using CSS.
		// example:
		//	|	<div dojoType="dojox.widget.Rotator" jsId="myRotator">
		//	|		<img src="/path/to/image1.jpg" thumbsrc="/path/to/thumb1.jpg" alt="Image 1"/>
		//	|		<img src="/path/to/image2.jpg" thumbsrc="/path/to/thumb2.jpg" alt="Image 2"/>
		//	|	</div>
		//	|	<div dojoType="dojox.widget.rotator.ThumbnailController" rotator="myRotator"></div>

		// rotator: dojox/widget/Rotator
		//		An instance of a Rotator widget.
		rotator: null,

		constructor: function(/*Object*/params, /*DomNode|string*/node){
			// summary:
			//		Initializes the thumbnails and connect to the rotator.

			lang.mixin(this, params);

			this._domNode = node;

			// check if we have a valid rotator
			var r = this.rotator;
			if(r){
				// remove all of the controller's child nodes just in case
				while(node.firstChild){
					node.removeChild(node.firstChild);
				}

				for(var i=0; i<r.panes.length; i++){
					var n = r.panes[i].node,
						s = domAttr.get(n, "thumbsrc") || domAttr.get(n, "src"),
						t = domAttr.get(n, "alt") || "";

					if(/img/i.test(n.tagName)){
						(function(j){
							domConstruct.create("a", {
								classname: _css + ' ' + _css + j + ' ' + (j == r.idx ? _selected : ""),
								href: s,
								onclick: function(e){
									event.stop(e);
									if(r){
										r.control.apply(r, ["go", j]);
									}
								},
								title: t,
								innerHTML: '<img src="' + s + '" alt="' + t + '"/>'
							}, node);
						})(i);
					}
				}

				aspect.after(r, 'onUpdate', lang.hitch(this, "_onUpdate"), true);
			}
		},

		destroy: function(){
			// summary:
			//		Disconnect from the rotator.

			domConstruct.destroy(this._domNode);
		},

		_onUpdate: function(/*string*/type){
			// summary:
			//		Updates various pager controls when the rotator updates.

			var r = this.rotator; // no need to test if this is null since _onUpdate is only fired by the rotator
			if(type == "onAfterTransition"){
				var n = query('.' + _css, this._domNode).removeClass(_selected);
				if(r.idx < n.length){
					domClass.add(n[r.idx], _selected);
				}
			}
		}
	});

});
},
'dojox/widget/rotator/Fade':function(){
define([
	"dojo/_base/lang",
	"dojo/_base/fx",
	"dojo/dom-style",
	"dojo/fx"
], function(lang, baseFx, domStyle, fx) {

	function _fade(/*Object*/args, /*string*/action){
		// summary:
		//		Returns an animation of a fade out and fade in of the current and next
		//		panes.  It will either chain (fade) or combine (crossFade) the fade
		//		animations.
		var n = args.next.node;
		domStyle.set(n, {
			display: "",
			opacity: 0
		});

		args.node = args.current.node;

		return fx[action]([ /*dojo.Animation*/
			baseFx.fadeOut(args),
			baseFx.fadeIn(lang.mixin(args, { node: n }))
		]);
	}

	var exports = {
		fade: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that fades out the current pane, then fades in
			//		the next pane.
			return _fade(args, "chain"); /*dojo.Animation*/
		},

		crossFade: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that cross fades two rotator panes.
			return _fade(args, "combine"); /*dojo.Animation*/
		}
	};

	// back-compat, remove for 2.0
	lang.mixin(lang.getObject("dojox.widget.rotator"), exports);

	return exports;
});
},
'dojo/fx':function(){
define([
	"./_base/lang",
	"./Evented",
	"./_base/kernel",
	"./_base/array",
	"./aspect",
	"./_base/fx",
	"./dom",
	"./dom-style",
	"./dom-geometry",
	"./ready",
	"require" // for context sensitive loading of Toggler
], function(lang, Evented, dojo, arrayUtil, aspect, baseFx, dom, domStyle, geom, ready, require){

	// module:
	//		dojo/fx
	
	// For back-compat, remove in 2.0.
	if(!dojo.isAsync){
		ready(0, function(){
			var requires = ["./fx/Toggler"];
			require(requires);	// use indirection so modules not rolled into a build
		});
	}

	var coreFx = dojo.fx = {
		// summary:
		//		Effects library on top of Base animations
	};

	var _baseObj = {
			_fire: function(evt, args){
				if(this[evt]){
					this[evt].apply(this, args||[]);
				}
				return this;
			}
		};

	var _chain = function(animations){
		this._index = -1;
		this._animations = animations||[];
		this._current = this._onAnimateCtx = this._onEndCtx = null;

		this.duration = 0;
		arrayUtil.forEach(this._animations, function(a){
			this.duration += a.duration;
			if(a.delay){ this.duration += a.delay; }
		}, this);
	};
	_chain.prototype = new Evented();
	lang.extend(_chain, {
		_onAnimate: function(){
			this._fire("onAnimate", arguments);
		},
		_onEnd: function(){
			this._onAnimateCtx.remove();
			this._onEndCtx.remove();
			this._onAnimateCtx = this._onEndCtx = null;
			if(this._index + 1 == this._animations.length){
				this._fire("onEnd");
			}else{
				// switch animations
				this._current = this._animations[++this._index];
				this._onAnimateCtx = aspect.after(this._current, "onAnimate", lang.hitch(this, "_onAnimate"), true);
				this._onEndCtx = aspect.after(this._current, "onEnd", lang.hitch(this, "_onEnd"), true);
				this._current.play(0, true);
			}
		},
		play: function(/*int?*/ delay, /*Boolean?*/ gotoStart){
			if(!this._current){ this._current = this._animations[this._index = 0]; }
			if(!gotoStart && this._current.status() == "playing"){ return this; }
			var beforeBegin = aspect.after(this._current, "beforeBegin", lang.hitch(this, function(){
					this._fire("beforeBegin");
				}), true),
				onBegin = aspect.after(this._current, "onBegin", lang.hitch(this, function(arg){
					this._fire("onBegin", arguments);
				}), true),
				onPlay = aspect.after(this._current, "onPlay", lang.hitch(this, function(arg){
					this._fire("onPlay", arguments);
					beforeBegin.remove();
					onBegin.remove();
					onPlay.remove();
				}));
			if(this._onAnimateCtx){
				this._onAnimateCtx.remove();
			}
			this._onAnimateCtx = aspect.after(this._current, "onAnimate", lang.hitch(this, "_onAnimate"), true);
			if(this._onEndCtx){
				this._onEndCtx.remove();
			}
			this._onEndCtx = aspect.after(this._current, "onEnd", lang.hitch(this, "_onEnd"), true);
			this._current.play.apply(this._current, arguments);
			return this;
		},
		pause: function(){
			if(this._current){
				var e = aspect.after(this._current, "onPause", lang.hitch(this, function(arg){
						this._fire("onPause", arguments);
						e.remove();
					}), true);
				this._current.pause();
			}
			return this;
		},
		gotoPercent: function(/*Decimal*/percent, /*Boolean?*/ andPlay){
			this.pause();
			var offset = this.duration * percent;
			this._current = null;
			arrayUtil.some(this._animations, function(a){
				if(a.duration <= offset){
					this._current = a;
					return true;
				}
				offset -= a.duration;
				return false;
			});
			if(this._current){
				this._current.gotoPercent(offset / this._current.duration, andPlay);
			}
			return this;
		},
		stop: function(/*boolean?*/ gotoEnd){
			if(this._current){
				if(gotoEnd){
					for(; this._index + 1 < this._animations.length; ++this._index){
						this._animations[this._index].stop(true);
					}
					this._current = this._animations[this._index];
				}
				var e = aspect.after(this._current, "onStop", lang.hitch(this, function(arg){
						this._fire("onStop", arguments);
						e.remove();
					}), true);
				this._current.stop();
			}
			return this;
		},
		status: function(){
			return this._current ? this._current.status() : "stopped";
		},
		destroy: function(){
			if(this._onAnimateCtx){ this._onAnimateCtx.remove(); }
			if(this._onEndCtx){ this._onEndCtx.remove(); }
		}
	});
	lang.extend(_chain, _baseObj);

	coreFx.chain = function(/*dojo/_base/fx.Animation[]*/ animations){
		// summary:
		//		Chain a list of `dojo/_base/fx.Animation`s to run in sequence
		//
		// description:
		//		Return a `dojo/_base/fx.Animation` which will play all passed
		//		`dojo/_base/fx.Animation` instances in sequence, firing its own
		//		synthesized events simulating a single animation. (eg:
		//		onEnd of this animation means the end of the chain,
		//		not the individual animations within)
		//
		// example:
		//	Once `node` is faded out, fade in `otherNode`
		//	|	require(["dojo/fx"], function(fx){
		//	|		fx.chain([
		//	|			fx.fadeIn({ node:node }),
		//	|			fx.fadeOut({ node:otherNode })
		//	|		]).play();
		//	|	});
		//
		return new _chain(animations); // dojo/_base/fx.Animation
	};

	var _combine = function(animations){
		this._animations = animations||[];
		this._connects = [];
		this._finished = 0;

		this.duration = 0;
		arrayUtil.forEach(animations, function(a){
			var duration = a.duration;
			if(a.delay){ duration += a.delay; }
			if(this.duration < duration){ this.duration = duration; }
			this._connects.push(aspect.after(a, "onEnd", lang.hitch(this, "_onEnd"), true));
		}, this);

		this._pseudoAnimation = new baseFx.Animation({curve: [0, 1], duration: this.duration});
		var self = this;
		arrayUtil.forEach(["beforeBegin", "onBegin", "onPlay", "onAnimate", "onPause", "onStop", "onEnd"],
			function(evt){
				self._connects.push(aspect.after(self._pseudoAnimation, evt,
					function(){ self._fire(evt, arguments); },
				true));
			}
		);
	};
	lang.extend(_combine, {
		_doAction: function(action, args){
			arrayUtil.forEach(this._animations, function(a){
				a[action].apply(a, args);
			});
			return this;
		},
		_onEnd: function(){
			if(++this._finished > this._animations.length){
				this._fire("onEnd");
			}
		},
		_call: function(action, args){
			var t = this._pseudoAnimation;
			t[action].apply(t, args);
		},
		play: function(/*int?*/ delay, /*Boolean?*/ gotoStart){
			this._finished = 0;
			this._doAction("play", arguments);
			this._call("play", arguments);
			return this;
		},
		pause: function(){
			this._doAction("pause", arguments);
			this._call("pause", arguments);
			return this;
		},
		gotoPercent: function(/*Decimal*/percent, /*Boolean?*/ andPlay){
			var ms = this.duration * percent;
			arrayUtil.forEach(this._animations, function(a){
				a.gotoPercent(a.duration < ms ? 1 : (ms / a.duration), andPlay);
			});
			this._call("gotoPercent", arguments);
			return this;
		},
		stop: function(/*boolean?*/ gotoEnd){
			this._doAction("stop", arguments);
			this._call("stop", arguments);
			return this;
		},
		status: function(){
			return this._pseudoAnimation.status();
		},
		destroy: function(){
			arrayUtil.forEach(this._connects, function(handle){
				handle.remove();
			});
		}
	});
	lang.extend(_combine, _baseObj);

	coreFx.combine = function(/*dojo/_base/fx.Animation[]*/ animations){
		// summary:
		//		Combine a list of `dojo/_base/fx.Animation`s to run in parallel
		//
		// description:
		//		Combine an array of `dojo/_base/fx.Animation`s to run in parallel,
		//		providing a new `dojo/_base/fx.Animation` instance encompasing each
		//		animation, firing standard animation events.
		//
		// example:
		//	Fade out `node` while fading in `otherNode` simultaneously
		//	|	require(["dojo/fx"], function(fx){
		//	|		fx.combine([
		//	|			fx.fadeIn({ node:node }),
		//	|			fx.fadeOut({ node:otherNode })
		//	|		]).play();
		//	|	});
		//
		// example:
		//	When the longest animation ends, execute a function:
		//	|	require(["dojo/fx"], function(fx){
		//	|		var anim = fx.combine([
		//	|			fx.fadeIn({ node: n, duration:700 }),
		//	|			fx.fadeOut({ node: otherNode, duration: 300 })
		//	|		]);
		//	|		aspect.after(anim, "onEnd", function(){
		//	|			// overall animation is done.
		//	|		}, true);
		//	|		anim.play(); // play the animation
		//	|	});
		//
		return new _combine(animations); // dojo/_base/fx.Animation
	};

	coreFx.wipeIn = function(/*Object*/ args){
		// summary:
		//		Expand a node to it's natural height.
		//
		// description:
		//		Returns an animation that will expand the
		//		node defined in 'args' object from it's current height to
		//		it's natural height (with no scrollbar).
		//		Node must have no margin/border/padding.
		//
		// args: Object
		//		A hash-map of standard `dojo/_base/fx.Animation` constructor properties
		//		(such as easing: node: duration: and so on)
		//
		// example:
		//	|	require(["dojo/fx"], function(fx){
		//	|		fx.wipeIn({
		//	|			node:"someId"
		//	|		}).play()
		//	|	});

		var node = args.node = dom.byId(args.node), s = node.style, o;

		var anim = baseFx.animateProperty(lang.mixin({
			properties: {
				height: {
					// wrapped in functions so we wait till the last second to query (in case value has changed)
					start: function(){
						// start at current [computed] height, but use 1px rather than 0
						// because 0 causes IE to display the whole panel
						o = s.overflow;
						s.overflow = "hidden";
						if(s.visibility == "hidden" || s.display == "none"){
							s.height = "1px";
							s.display = "";
							s.visibility = "";
							return 1;
						}else{
							var height = domStyle.get(node, "height");
							return Math.max(height, 1);
						}
					},
					end: function(){
						return node.scrollHeight;
					}
				}
			}
		}, args));

		var fini = function(){
			s.height = "auto";
			s.overflow = o;
		};
		aspect.after(anim, "onStop", fini, true);
		aspect.after(anim, "onEnd", fini, true);

		return anim; // dojo/_base/fx.Animation
	};

	coreFx.wipeOut = function(/*Object*/ args){
		// summary:
		//		Shrink a node to nothing and hide it.
		//
		// description:
		//		Returns an animation that will shrink node defined in "args"
		//		from it's current height to 1px, and then hide it.
		//
		// args: Object
		//		A hash-map of standard `dojo/_base/fx.Animation` constructor properties
		//		(such as easing: node: duration: and so on)
		//
		// example:
		//	|	require(["dojo/fx"], function(fx){
		//	|		fx.wipeOut({ node:"someId" }).play()
		//	|	});

		var node = args.node = dom.byId(args.node), s = node.style, o;

		var anim = baseFx.animateProperty(lang.mixin({
			properties: {
				height: {
					end: 1 // 0 causes IE to display the whole panel
				}
			}
		}, args));

		aspect.after(anim, "beforeBegin", function(){
			o = s.overflow;
			s.overflow = "hidden";
			s.display = "";
		}, true);
		var fini = function(){
			s.overflow = o;
			s.height = "auto";
			s.display = "none";
		};
		aspect.after(anim, "onStop", fini, true);
		aspect.after(anim, "onEnd", fini, true);

		return anim; // dojo/_base/fx.Animation
	};

	coreFx.slideTo = function(/*Object*/ args){
		// summary:
		//		Slide a node to a new top/left position
		//
		// description:
		//		Returns an animation that will slide "node"
		//		defined in args Object from its current position to
		//		the position defined by (args.left, args.top).
		//
		// args: Object
		//		A hash-map of standard `dojo/_base/fx.Animation` constructor properties
		//		(such as easing: node: duration: and so on). Special args members
		//		are `top` and `left`, which indicate the new position to slide to.
		//
		// example:
		//	|	.slideTo({ node: node, left:"40", top:"50", units:"px" }).play()

		var node = args.node = dom.byId(args.node),
			top = null, left = null;

		var init = (function(n){
			return function(){
				var cs = domStyle.getComputedStyle(n);
				var pos = cs.position;
				top = (pos == 'absolute' ? n.offsetTop : parseInt(cs.top) || 0);
				left = (pos == 'absolute' ? n.offsetLeft : parseInt(cs.left) || 0);
				if(pos != 'absolute' && pos != 'relative'){
					var ret = geom.position(n, true);
					top = ret.y;
					left = ret.x;
					n.style.position="absolute";
					n.style.top=top+"px";
					n.style.left=left+"px";
				}
			};
		})(node);
		init();

		var anim = baseFx.animateProperty(lang.mixin({
			properties: {
				top: args.top || 0,
				left: args.left || 0
			}
		}, args));
		aspect.after(anim, "beforeBegin", init, true);

		return anim; // dojo/_base/fx.Animation
	};

	return coreFx;
});

},
'dojox/widget/rotator/Pan':function(){
define([
	"dojo/_base/array",
	"dojo/_base/connect",
	"dojo/_base/lang",
	"dojo/dom-style",
	"dojo/_base/fx",
	"dojo/fx"
], function(array, connect, lang, domStyle, baseFx, fx) {

	// Constants used to identify which edge the pane pans in from.
	var DOWN = 0,
		RIGHT = 1,
		UP = 2,
		LEFT = 3;

	function _pan(/*int*/type, /*Object*/args){
		// summary:
		//		Handles the preparation of the dom node and creates the dojo.Animation object.
		var n = args.next.node,
			r = args.rotatorBox,
			m = type % 2,
			a = m ? "left" : "top",
			s = (m ? r.w : r.h) * (type < 2 ? -1 : 1),
			p = {},
			q = {};

		domStyle.set(n, "display", "");

		p[a] = {
			start: 0,
			end: -s
		};

		q[a] = {
			start: s,
			end: 0
		};

		return fx.combine([ /*dojo.Animation*/
			baseFx.animateProperty({
				node: args.current.node,
				duration: args.duration,
				properties: p,
				easing: args.easing
			}),
			baseFx.animateProperty({
				node: n,
				duration: args.duration,
				properties: q,
				easing: args.easing
			})
		]);
	}

	function _setZindex(/*DomNode*/n, /*int*/z){
		// summary:
		//		Helper function for continuously panning.
		domStyle.set(n, "zIndex", z);
	}

	var exports = {
		pan: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that either pans left or right to the next pane.
			//		The actual direction depends on the order of the panes.
			//
			//		If panning forward from index 1 to 3, it will perform a pan left. If panning
			//		backwards from 5 to 1, then it will perform a pan right.
			//
			//		If the parameter "continuous" is set to true, it will return an animation
			//		chain of several pan animations of each intermediate pane panning. For
			//		example, if you pan forward from 1 to 3, it will return an animation panning
			//		left from 1 to 2 and then 2 to 3.
			//
			//		If an easing is specified, it will be applied to each pan transition.  For
			//		example, if you are panning from pane 1 to pane 5 and you set the easing to
			//		"dojo.fx.easing.elasticInOut", then it will "wobble" 5 times, once for each
			//		pan transition.
			//
			//		If the parameter "wrap" is set to true, it will pan to the next pane using
			//		the shortest distance in the array of panes. For example, if there are 6
			//		panes, then panning from 5 to 1 will pan forward (left) from pane 5 to 6 and
			//		6 to 1.  If the distance is the same either going forward or backwards, then
			//		it will always pan forward (left).
			//
			//		A continuous pan will use the target pane's duration to pan all intermediate
			//		panes.  To use the target's pane duration for each intermediate pane, then
			//		set the "quick" parameter to "false".

			var w = args.wrap,
				p = args.rotator.panes,
				len = p.length,
				z = len,
				j = args.current.idx,
				k = args.next.idx,
				nw = Math.abs(k - j),
				ww = Math.abs((len - Math.max(j, k)) + Math.min(j, k)) % len,
				_forward = j < k,
				_dir = LEFT,
				_pans = [],
				_nodes = [],
				_duration = args.duration;

			// default to pan left, but check if we should pan right.
			// need to take into account wrapping.
			if((!w && !_forward) || (w && (_forward && nw > ww || !_forward && nw < ww))){
				_dir = RIGHT;
			}

			if(args.continuous){
				// if continuous pans are quick, then divide the duration by the number of panes
				if(args.quick){
					_duration = Math.round(_duration / (w ? Math.min(ww, nw) : nw));
				}

				// set the current pane's z-index
				_setZindex(p[j].node, z--);

				var f = (_dir == LEFT);

				// loop and set z-indexes and get all pan animations
				while(1){
					// set the current pane
					var i = j;

					// increment/decrement the next pane's index
					if(f){
						if(++j >= len){
							j = 0;
						}
					}else{
						if(--j < 0){
							j = len - 1;
						}
					}

					var x = p[i],
						y = p[j];

					// set next pane's z-index
					_setZindex(y.node, z--);

					// build the pan animation
					_pans.push(_pan(_dir, lang.mixin({
						easing: function(m){ return m; } // continuous gets a linear easing by default
					}, args, {
						current: x,
						next: y,
						duration: _duration
					})));

					// if we're done, then break out of the loop
					if((f && j == k) || (!f && j == k)){
						break;
					}

					// this must come after the break... we don't want the last pane to get it's
					// styles reset.
					_nodes.push(y.node);
				}

				// build the chained animation of all pan animations
				var _anim = fx.chain(_pans),

					// clean up styles when the chained animation finishes
					h = connect.connect(_anim, "onEnd", function(){
						connect.disconnect(h);
						array.forEach(_nodes, function(q){
							domStyle.set(q, {
								display: "none",
								left: 0,
								opacity: 1,
								top: 0,
								zIndex: 0
							});
						});
					});

				return _anim;
			}

			// we're not continuous, so just return a normal pan animation
			return _pan(_dir, args); /*dojo.Animation*/
		},

		panDown: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that pans in the next rotator pane from the top.
			return _pan(DOWN, args); /*dojo.Animation*/
		},

		panRight: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that pans in the next rotator pane from the right.
			return _pan(RIGHT, args); /*dojo.Animation*/
		},

		panUp: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that pans in the next rotator pane from the bottom.
			return _pan(UP, args); /*dojo.Animation*/
		},

		panLeft: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that pans in the next rotator pane from the left.
			return _pan(LEFT, args); /*dojo.Animation*/
		}
	};

	// back-compat, remove for 2.0
	lang.mixin(lang.getObject("dojox.widget.rotator"), exports);

	return exports;
});
},
'dojox/widget/rotator/PanFade':function(){
define([
	"dojo/_base/array",
	"dojo/_base/connect",
	"dojo/_base/lang",
	"dojo/_base/fx",
	"dojo/dom-style",
	"dojo/fx"
], function(array, connect, lang, baseFx, domStyle, fx) {

	// Constants used to identify which edge the pane pans in from.
	var DOWN = 0,
		RIGHT = 1,
		UP = 2,
		LEFT = 3;

	function _pan(/*int*/type, /*Object*/args){
		// summary:
		//		Handles the preparation of the dom node and creates the dojo.Animation object.
		var j = {
				node: args.current.node,
				duration: args.duration,
				easing: args.easing
			},
			k = {
				node: args.next.node,
				duration: args.duration,
				easing: args.easing
			},
			r = args.rotatorBox,
			m = type % 2,
			a = m ? "left" : "top",
			s = (m ? r.w : r.h) * (type < 2 ? -1 : 1),
			p = {},
			q = {};

		domStyle.set(k.node, {
			display: "",
			opacity: 0
		});

		p[a] = {
			start: 0,
			end: -s
		};

		q[a] = {
			start: s,
			end: 0
		};

		return fx.combine([ /*dojo.Animation*/
			baseFx.animateProperty(lang.mixin({ properties: p }, j)),
			baseFx.fadeOut(j),
			baseFx.animateProperty(lang.mixin({ properties: q }, k)),
			baseFx.fadeIn(k)
		]);
	}

	function _setZindex(/*DomNode*/n, /*int*/z){
		// summary:
		//		Helper function for continuously panning.
		domStyle.set(n, "zIndex", z);
	}

	var exports = {
		panFade: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that either pans left or right to the next pane.
			//		The actual direction depends on the order of the panes.
			//
			//		If panning forward from index 1 to 3, it will perform a pan left. If panning
			//		backwards from 5 to 1, then it will perform a pan right.
			//
			//		If the parameter "continuous" is set to true, it will return an animation
			//		chain of several pan animations of each intermediate pane panning. For
			//		example, if you pan forward from 1 to 3, it will return an animation panning
			//		left from 1 to 2 and then 2 to 3.
			//
			//		If an easing is specified, it will be applied to each pan transition.  For
			//		example, if you are panning from pane 1 to pane 5 and you set the easing to
			//		"dojo.fx.easing.elasticInOut", then it will "wobble" 5 times, once for each
			//		pan transition.
			//
			//		If the parameter "wrap" is set to true, it will pan to the next pane using
			//		the shortest distance in the array of panes. For example, if there are 6
			//		panes, then panning from 5 to 1 will pan forward (left) from pane 5 to 6 and
			//		6 to 1.  If the distance is the same either going forward or backwards, then
			//		it will always pan forward (left).
			//
			//		A continuous pan will use the target pane's duration to pan all intermediate
			//		panes.  To use the target's pane duration for each intermediate pane, then
			//		set the "quick" parameter to "false".

			var w = args.wrap,
				p = args.rotator.panes,
				len = p.length,
				z = len,
				j = args.current.idx,
				k = args.next.idx,
				nw = Math.abs(k - j),
				ww = Math.abs((len - Math.max(j, k)) + Math.min(j, k)) % len,
				_forward = j < k,
				_dir = LEFT,
				_pans = [],
				_nodes = [],
				_duration = args.duration;

			// default to pan left, but check if we should pan right.
			// need to take into account wrapping.
			if((!w && !_forward) || (w && (_forward && nw > ww || !_forward && nw < ww))){
				_dir = RIGHT;
			}

			if(args.continuous){
				// if continuous pans are quick, then divide the duration by the number of panes
				if(args.quick){
					_duration = Math.round(_duration / (w ? Math.min(ww, nw) : nw));
				}

				// set the current pane's z-index
				_setZindex(p[j].node, z--);

				var f = (_dir == LEFT);

				// loop and set z-indexes and get all pan animations
				while(1){
					// set the current pane
					var i = j;

					// increment/decrement the next pane's index
					if(f){
						if(++j >= len){
							j = 0;
						}
					}else{
						if(--j < 0){
							j = len - 1;
						}
					}

					var x = p[i],
						y = p[j];

					// set next pane's z-index
					_setZindex(y.node, z--);

					// build the pan animation
					_pans.push(_pan(_dir, lang.mixin({
						easing: function(m){ return m; } // continuous gets a linear easing by default
					}, args, {
						current: x,
						next: y,
						duration: _duration
					})));

					// if we're done, then break out of the loop
					if((f && j == k) || (!f && j == k)){
						break;
					}

					// this must come after the break... we don't want the last pane to get it's
					// styles reset.
					_nodes.push(y.node);
				}

				// build the chained animation of all pan animations
				var _anim = fx.chain(_pans);

				// clean up styles when the chained animation finishes
				var h = connect.connect(_anim, "onEnd", function(){
					connect.disconnect(h);
					array.forEach(_nodes, function(q){
						domStyle.set(q, {
							display: "none",
							left: 0,
							opacity: 1,
							top: 0,
							zIndex: 0
						});
					});
				});

				return _anim;
			}

			// we're not continuous, so just return a normal pan animation
			return _pan(_dir, args); /*dojo.Animation*/
		},

		panFadeDown: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that pans in the next rotator pane from the top.
			return _pan(DOWN, args); /*dojo.Animation*/
		},

		panFadeRight: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that pans in the next rotator pane from the right.
			return _pan(RIGHT, args); /*dojo.Animation*/
		},

		panFadeUp: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that pans in the next rotator pane from the bottom.
			return _pan(UP, args); /*dojo.Animation*/
		},

		panFadeLeft: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that pans in the next rotator pane from the left.
			return _pan(LEFT, args); /*dojo.Animation*/
		}
	};

	// back-compat, remove for 2.0
	lang.mixin(lang.getObject("dojox.widget.rotator"), exports);

	return exports;
});
},
'dojox/widget/rotator/Slide':function(){
define([
	"dojo/_base/lang",
	"dojo/_base/fx",
	"dojo/dom-style"
], function(lang, baseFx, domStyle){

	// Constants used to identify which edge the pane slides in from.
	var DOWN = 0,
		RIGHT = 1,
		UP = 2,
		LEFT = 3;

	function _slide(/*int*/type, /*Object*/args){
		// summary:
		//		Handles the preparation of the dom node and creates the dojo.Animation object.
		var node = args.node = args.next.node,
			r = args.rotatorBox,
			m = type % 2,
			s = (m ? r.w : r.h) * (type < 2 ? -1 : 1);

		domStyle.set(node, {
			display: "",
			zIndex: (domStyle.get(args.current.node, "zIndex") || 1) + 1
		});

		if(!args.properties){
			args.properties = {};
		}
		args.properties[m ? "left" : "top"] = {
			start: s,
			end: 0
		};

		return baseFx.animateProperty(args); /*dojo.Animation*/
	}

	var exports = {
		slideDown: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that slides in the next rotator pane from the top.
			return _slide(DOWN, args); /*dojo.Animation*/
		},

		slideRight: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that slides in the next rotator pane from the right.
			return _slide(RIGHT, args); /*dojo.Animation*/
		},

		slideUp: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that slides in the next rotator pane from the bottom.
			return _slide(UP, args); /*dojo.Animation*/
		},

		slideLeft: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that slides in the next rotator pane from the left.
			return _slide(LEFT, args); /*dojo.Animation*/
		}
	};

	// back-compat, remove for 2.0
	lang.mixin(lang.getObject("dojox.widget.rotator"), exports);

	return exports;
});
},
'dojox/widget/rotator/Wipe':function(){
define([
	"dojo/_base/lang",
	"dojo/_base/fx",
	"dojo/dom-style"
], function(lang, fx, domStyle) {

	// Constants used to identify which clip edge is being wiped. The values are
	// the index of the clip array that is changed during the animation.
	var DOWN = 2,
		RIGHT = 3,
		UP = 0,
		LEFT = 1;

	function _clipArray(/*int*/type, /*int*/w, /*int*/h, /*number*/x){
		// summary:
		//		Returns an array containing the down, right, up, and
		//		left clip region based on the type.  If "x" is specified,
		//		then it is applied to the appropriate clipping edge.
		var a = [0, w, 0, 0]; // default to the top edge
		if(type == RIGHT){
			a = [0, w, h, w];
		}else if(type == UP){
			a = [h, w, h, 0];
		}else if(type == LEFT){
			a = [0, 0, h, 0];
		}
		if(x != null){
			a[type] = type == DOWN || type == LEFT ? x : (type % 2 ? w : h) - x;
		}
		return a; /*Array*/
	}

	function _setClip(/*DomNode*/n, /*int*/type, /*int*/w, /*int*/h, /*number*/x){
		// summary:
		//		Sets the clip region of the node. If a type is passed in then we
		//		return a rect(), otherwise return "auto".
		domStyle.set(n, "clip", type == null ? "auto" : "rect(" + _clipArray(type, w, h, x).join("px,") + "px)");
	}

	function _wipe(/*int*/type, /*Object*/args){
		// summary:
		//		Handles the preparation of the dom node and creates the Animation object.
		var node = args.next.node,
			w = args.rotatorBox.w,
			h = args.rotatorBox.h;

		domStyle.set(node, {
			display: "",
			zIndex: (domStyle.get(args.current.node, "zIndex") || 1) + 1
		});

		_setClip(node, type, w, h);

		return new fx.Animation(lang.mixin({
			node: node,
			curve: [0, type % 2 ? w : h],
			onAnimate: function(x){
				_setClip(node, type, w, h, parseInt(x));
			}
		}, args));
	}

	var exports = {
		wipeDown: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that wipes in the next rotator pane from the top.
			return _wipe(DOWN, args); /*dojo.Animation*/
		},

		wipeRight: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that wipes in the next rotator pane from the right.
			return _wipe(RIGHT, args); /*dojo.Animation*/
		},

		wipeUp: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that wipes in the next rotator pane from the bottom.
			return _wipe(UP, args); /*dojo.Animation*/
		},

		wipeLeft: function(/*Object*/args){
			// summary:
			//		Returns a dojo.Animation that wipes in the next rotator pane from the left.
			return _wipe(LEFT, args); /*dojo.Animation*/
		}
	};

	// back-compat, remove for 2.0
	lang.mixin(lang.getObject("dojox.widget.rotator"), exports);

	return exports;
});
}}});
define("dojox/widget/AutoRotator", [
	"dojo/_base/declare",
	"dojo/_base/array",
	"dojo/_base/lang",
	"dojo/on",
	"dojo/mouse",
	"dojox/widget/Rotator"
], function(declare, array, lang, on, mouse, Rotator) {

return declare("dojox.widget.AutoRotator", Rotator,{
	// summary:
	//		A rotator that automatically transitions between child nodes.
	// description:
	//		Adds automatic rotating to the dojox.widget.Rotator.  The
	//		AutoRotator has parameters that control how user input can
	//		affect the rotator including a suspend when hovering over the
	//		rotator and pausing when the user manually advances to another
	//		pane.
	// example:
	//	|	<div dojoType="dojox.widget.AutoRotator" duration="3000">
	//	|		<div>
	//	|			Pane 1!
	//	|		</div>
	//	|		<div duration="5000">
	//	|			Pane 2 with an overrided duration!
	//	|		</div>
	//	|	</div>

	// suspendOnHover: Boolean
	//		Pause the rotator when the mouse hovers over it.
	suspendOnHover: false,

	// duration: int
	//		The time in milliseconds before transitioning to the next pane.  The
	//		default value is 4000 (4 seconds).
	duration: 4000,
	
	// autoStart: Boolean
	//		Starts the timer to transition children upon creation.
	autoStart: true,
	
	// pauseOnManualChange: Boolean
	//		Pause the rotator when the pane is changed or a controller's next or
	//		previous buttons are clicked.
	pauseOnManualChange: false,
	
	// cycles: int
	//		Number of cycles before pausing.
	cycles: -1,

	// random: Boolean
	//		Determines if the panes should cycle randomly.
	random: false,

	// reverse: Boolean
	//		Causes the rotator to rotate in reverse order.
	reverse: false,

  constructor: function(){
	// summary:
	//		Initializes the timer and connect to the rotator.

			var _t = this;

			// validate the cycles counter
			if(_t.cycles-0 == _t.cycles && _t.cycles > 0){
				// we need to add 1 because we decrement cycles before the animation starts
				_t.cycles++;
			}else{
				_t.cycles = _t.cycles ? -1 : 0;
			}

			// wire up the mouse hover events
			_t._signals = [
				on(_t._domNode, mouse.enter, function(){
					// temporarily suspend the cycling, but don't officially pause
					// it and don't allow suspending if we're transitioning
					if(_t.suspendOnHover && !_t.anim && !_t.wfe){
						var t = _t._endTime,
							n = _t._now();
						_t._suspended = true;
						_t._resetTimer();
						_t._resumeDuration = t > n ? t - n : 0.01;
					}
				}),

				on(_t._domNode, mouse.leave, function(){
					// if we were playing, resume playback unless were in the
					// middle of a transition
					if(_t.suspendOnHover && !_t.anim){
						_t._suspended = false;
						if(_t.playing && !_t.wfe){
							_t.play(true);
						}
					}
				})
			];

			// everything is ready, so start
			if(_t.autoStart && _t.panes.length > 1){
				// start playing
				_t.play();
			}else{
				// since we're not playing, lets pause
				_t.pause();
			}
		},

		destroy: function(){
			// summary:
			//		Disconnect the AutoRotator's events.
			array.forEach(this._signals, function(signal) { signal.remove(); });
			delete this._signals;
			dojo.forEach(this._connects, dojo.disconnect);
			this.inherited(arguments);
		},

		play: function(/*Boolean?*/skipCycleDecrement, /*Boolean?*/skipDuration){
			// summary:
			//		Sets the state to "playing" and schedules the next cycle to run.
			this.playing = true;
			this._resetTimer();

			// don't decrement the count if we're resuming play
			if(skipCycleDecrement !== true && this.cycles > 0){
				this.cycles--;
			}

			if(this.cycles == 0){
				// we have reached the number of cycles, so pause
				this.pause();
			}else if(!this._suspended){
				this.onUpdate("play");
				// if we haven't been suspended, then grab the duration for this pane and
				// schedule a cycle to be run
				if(skipDuration){
					this._cycle();
				}else{
					var r = (this._resumeDuration || 0)-0,
						u = (r > 0 ? r : (this.panes[this.idx].duration || this.duration))-0;
					// call _cycle() after a duration and pass in false so it isn't manual
					this._resumeDuration = 0;
					this._endTime = this._now() + u;
					this._timer = setTimeout(lang.hitch(this, "_cycle", false), u);
				}
			}
		},

		pause: function(){
			// summary:
			//		Sets the state to "not playing" and clears the cycle timer.
			this.playing = this._suspended = false;
			this.cycles = -1;
			this._resetTimer();

			// notify the controllers we're paused
			this.onUpdate("pause");
		},

		_now: function(){
			// summary:
			//		Helper function to return the current system time in milliseconds.
			return (new Date()).getTime(); // int
		},

		_resetTimer: function(){
			// summary:
			//		Resets the timer used to schedule the next transition.
			clearTimeout(this._timer);
		},

		_cycle: function(/*Boolean|int?*/manual){
			// summary:
			//		Cycles the rotator to the next/previous pane.
			var _t = this,
				i = _t.idx,
				j;

			if(_t.random){
				// make sure we don't randomly pick the pane we're already on
				do{
					j = Math.floor(Math.random() * _t.panes.length + 1);
				}while(j == i);
			}else{
				j = i + (_t.reverse ? -1 : 1)
			}

			// rotate!
			var def = _t.go(j);

			if(def){
				def.addCallback(function(/*Boolean?*/skipDuration){
					_t.onUpdate("cycle");
					if(_t.playing){
						_t.play(false, skipDuration);
					}
				});
			}
		},

		onManualChange: function(/*string*/action){
			// summary:
			//		Override the Rotator's onManualChange so we can pause.

			this.cycles = -1;

			// obviously we don't want to pause if play was just clicked
			if(action != "play"){
				this._resetTimer();
				if(this.pauseOnManualChange){
					this.pause();
				}
			}

			if(this.playing){
				this.play();
			}
		}		
});
});
