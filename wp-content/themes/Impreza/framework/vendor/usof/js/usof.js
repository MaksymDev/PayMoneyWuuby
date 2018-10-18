/**
 * Retrieve/set/erase dom modificator class <mod>_<value> for UpSolution CSS Framework
 * @param {String} mod Modificator namespace
 * @param {String} [value] Value
 * @returns {string|jQuery}
 */
jQuery.fn.usMod = function(mod, value){
	if (this.length == 0) return this;
	// Remove class modificator
	if (value === false) {
		return this.each(function(){
			this.className = this.className.replace(new RegExp('(^| )' + mod + '\_[a-zA-Z0-9\_\-]+( |$)'), '$2');
		});
	}
	var pcre = new RegExp('^.*?' + mod + '\_([a-zA-Z0-9\_\-]+).*?$'),
		arr;
	// Retrieve modificator
	if (value === undefined) {
		return (arr = pcre.exec(this.get(0).className)) ? arr[1] : false;
	}
	// Set modificator
	else {
		var regexp = new RegExp('(^| )' + mod + '\_[a-zA-Z0-9\_\-]+( |$)');
		return this.each(function(){
			if (this.className.match(regexp)) {
				this.className = this.className.replace(regexp, '$1' + mod + '_' + value + '$2');
			} else {
				this.className += ' ' + mod + '_' + value;
			}
		});
	}
};

/**
 * USOF Fields
 */
!function($){

	if (window.$usof === undefined) window.$usof = {};
	if ($usof.mixins === undefined) $usof.mixins = {};

	// Prototype mixin for all classes working with events
	$usof.mixins.Events = {
		/**
		 * Attach a handler to an event for the class instance
		 * @param {String} eventType A string containing event type, such as 'beforeShow' or 'change'
		 * @param {Function} handler A function to execute each time the event is triggered
		 */
		on: function(eventType, handler){
			if (this.$$events === undefined) this.$$events = {};
			if (this.$$events[eventType] === undefined) this.$$events[eventType] = [];
			this.$$events[eventType].push(handler);
			return this;
		},
		/**
		 * Remove a previously-attached event handler from the class instance
		 * @param {String} eventType A string containing event type, such as 'beforeShow' or 'change'
		 * @param {Function} [handler] The function that is to be no longer executed.
		 * @chainable
		 */
		off: function(eventType, handler){
			if (this.$$events === undefined || this.$$events[eventType] === undefined) return this;
			if (handler !== undefined) {
				var handlerPos = $.inArray(handler, this.$$events[eventType]);
				if (handlerPos != -1) {
					this.$$events[eventType].splice(handlerPos, 1);
				}
			} else {
				this.$$events[eventType] = [];
			}
			return this;
		},
		/**
		 * Execute all handlers and behaviours attached to the class instance for the given event type
		 * @param {String} eventType A string containing event type, such as 'beforeShow' or 'change'
		 * @param {Array} extraParameters Additional parameters to pass along to the event handler
		 * @chainable
		 */
		trigger: function(eventType, extraParameters){
			if (this.$$events === undefined || this.$$events[eventType] === undefined || this.$$events[eventType].length == 0) return this;
			var params = (arguments.length > 2 || !$.isArray(extraParameters)) ? Array.prototype.slice.call(arguments, 1) : extraParameters;
			// First argument is the current class instance
			params.unshift(this);
			for (var index = 0; index < this.$$events[eventType].length; index++) {
				this.$$events[eventType][index].apply(this.$$events[eventType][index], params);
			}
			return this;
		}
	};

	$usof.field = function(row, options){
		this.$row = $(row);
		this.type = this.$row.usMod('type');
		this.name = this.$row.data('name');
		this.id = this.$row.data('id');
		this.$input = this.$row.find('[name="' + this.name + '"]');
		this.inited = false;

		/**
		 * Boundable field events
		 */
		this.$$events = {
			beforeShow: [],
			afterShow: [],
			change: [],
			beforeHide: [],
			afterHide: []
		};

		// Overloading selected functions, moving parent functions to "parent" namespace: init => parentInit
		if ($usof.field[this.type] !== undefined) {
			for (var fn in $usof.field[this.type]) {
				if (!$usof.field[this.type].hasOwnProperty(fn)) continue;
				if (this[fn] !== undefined) {
					var parentFn = 'parent' + fn.charAt(0).toUpperCase() + fn.slice(1);
					this[parentFn] = this[fn];
				}
				this[fn] = $usof.field[this.type][fn];
			}
		}

		this.$row.data('usofField', this);

		// Init on first show
		var initEvent = function(){
			this.init(options);
			this.inited = true;
			this.off('beforeShow', initEvent);
		}.bind(this);
		this.on('beforeShow', initEvent);
	};
	$.extend($usof.field.prototype, $usof.mixins.Events, {
		init: function(){
			if (this._events === undefined) this._events = {};
			this._events.change = function(){
				this.trigger('change', [this.getValue()]);
			}.bind(this);
			this.$input.on('change', this._events.change);
		},
		getValue: function(){
			return this.$input.val();
		},
		setValue: function(value, quiet){
			this.$input.val(value);
			if (!quiet) this.trigger('change', [value]);
		}
	});

	/**
	 * USOF Field: Backup
	 */
	$usof.field['backup'] = {

		init: function(){
			this.$backupStatus = this.$row.find('.usof-backup-status');
			this.$btnBackup = this.$row.find('.usof-button.type_backup').on('click', this.backup.bind(this));
			this.$btnRestore = this.$row.find('.usof-button.type_restore').on('click', this.restore.bind(this));

			// JS Translations
			var $i18n = this.$row.find('.usof-backup-i18n');
			this.i18n = {};
			if ($i18n.length > 0) {
				this.i18n = $i18n[0].onclick() || {};
			}
		},

		backup: function(){
			this.$btnBackup.addClass('loading');
			$.ajax({
				type: 'POST',
				url: $usof.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_backup',
					_wpnonce: this.$row.closest('.usof-form').find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$row.closest('.usof-form').find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					this.$backupStatus.html(result.data.status);
					this.$btnBackup.removeClass('loading');
					this.$btnRestore.show();
				}.bind(this)
			});
		},

		restore: function(){
			if (!confirm(this.i18n.restore_confirm)) return;
			this.$btnRestore.addClass('loading');
			$.ajax({
				type: 'POST',
				url: $usof.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_restore_backup',
					_wpnonce: this.$row.closest('.usof-form').find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$row.closest('.usof-form').find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					this.$btnRestore.removeClass('loading');
					alert(result.data.message);
					location.reload();
				}.bind(this)
			});
		}

	};

	/**
	 * USOF Field: Checkbox
	 */
	$usof.field['checkboxes'] = {

		getValue: function(){
			var value = [];
			$.each(this.$input, function(){
				if (this.checked) value.push(this.value);
			});
			return value;
		},

		setValue: function(value, quiet){
			$.each(this.$input, function(){
				$(this).attr('checked', ($.inArray(this.value, value) != -1) ? 'checked' : false);
			});
		}

	};

	$usof.field['check_table'] = {

		getValue: function(){
			var value = [];
			$.each(this.$input, function(){
				if (this.checked) value.push(this.value);
			});
			return value;
		},

		setValue: function(value, quiet){
			$.each(this.$input, function(){
				$(this).attr('checked', ($.inArray(this.value, value) != -1) ? 'checked' : false);
			});
		}

	};

	/**
	 * USOF Field: Color
	 */
	$usof.field['color'] = {

		init: function(options){
			this.$color = this.$row.find('.usof-color');
			this.$preview = this.$row.find('.usof-color-preview');
			this.$clear = this.$row.find('.usof-color-clear');
			this.$palette = this.$row.find('.colpick_palette');
			// Set white text color for dark backgrounds
			if (this.$input.val() != 'inherit' && this.$input.val() != 'transparent') {
				this.invertInputColors($.colpick.hexToRgba(this.$input.val()));
			}
			this.$input.colpick({
				layout: 'hex',
				color: (this.$input.val() || ''),
				submit: false,
				onChange: function(hsb, hex, rgb, el, bySetColor){
					this.$preview.css('background', hex);
					this.invertInputColors(rgb);
					this.$input.toggleClass('with_alpha', hex.substr(0, 5) == 'rgba(');
					if (!bySetColor) this.$input.val(hex);
				}.bind(this),
				onHide: function(){
					this.$color.removeClass('active');
					this.trigger('change', this.$input.val());
					this.passToSchemes();
				}.bind(this),
				onShow: function(){
					this.$color.addClass('active');
				}.bind(this)
			});
			this.$input.on('keyup', function(){
				var value = this.$input.val() || '';
				if (value == '') {
					this.$preview.removeAttr('style');
					return;
				}
				if (value == 'inherit' || value == 'transparent') {
					this.$preview.css('background', value);
					this.$input.removeClass('white');
					return;
				}
				if ((value.length == 3 || value.length == 4) && (m = /^\#?([0-9a-fA-F]{3})$/.exec(value))){
					value = '#' + m[1][0] + m[1][0] + m[1][1] + m[1][1] + m[1][2] + m[1][2];
				}
				if ((value.length == 6) && (m = /^([0-9a-fA-F]{6})$/.exec(value))){
					value = '#' + m[1];
				}
				this.$input.colpickSetColor(value);
			}.bind(this));
			this.$input.on('change', function(){
				this.setValue(this.$input.val());
			}.bind(this));
			this.$clear.on('click', function(){
				this.setValue('');
				this.passToSchemes();
			}.bind(this));
			this.$palette.on('mousedown', function(ev){
				if (ev) {
					ev.preventDefault();
					ev.stopPropagation();
				}
				this.colorPalette(ev);
			}.bind(this));
		},
		colorPalette: function(ev){
			var color, palette, colorId, max, currId,
				$target = $(ev.target),
				paletteClass = '.' + this.$palette[0].className;
			if ($target.is('span')) {
				color = $target.attr('style');
				if (m = /^[^:]*:([\s\S]*)$/.exec(color)) {
					this.setValue(m[1]);
				}
			}
			// Disable Add and Remove actions outside of USOF
			if ( !this.id.match(/usof/)) return;
			this.paletteSend = function(data){
				if ( ! data) return;
				$.ajax({
					type: 'POST',
					url: $usof.ajaxUrl,
					dataType: 'json',
					data: {
						action: 'usof_color_palette',
						color: JSON.stringify(data),
						_wpnonce: this.$row.closest('.usof-form').find('[name="_wpnonce"]').val(),
						_wp_http_referer: this.$row.closest('.usof-form').find('[name="_wp_http_referer"]').val()

					},
					success: function(result){
						$(paletteClass).html(result.data.output);
					}.bind(this)
				});
			}.bind(this);
			if ($target.hasClass('colpick_palette_add')) {
				$target.addClass('adding');
				palette = {value: this.$input.val()};
				max = this.$palette.children('.colpick_palette_value').length;
				if (max < 8) {
					this.paletteSend(palette);
				}
			}
			if ($target.hasClass('colpick_palette_delete')) {
				currId = $.inArray($target.closest('.colpick_palette_value')[0], this.$palette.find('.colpick_palette_value'));
				if (currId >= 0) {
					colorId = {colorId: currId};
					this.paletteSend(colorId);
					$target.closest('.colpick_palette_value').addClass('deleting');
				}
			}
		},
		passToSchemes: function(){
			// Pass changes to USOF Field: Style Scheme
			if ( !this.id.match(/usof_color/)) return;
			for (var fieldId in $usof.instance.fields) {
				if ( !$usof.instance.fields.hasOwnProperty(fieldId)) continue;
				if ( !$usof.instance.fields[fieldId].type.match(/style_scheme/)) continue;
				$usof.instance.fields[fieldId].setSchemeButtonStates('color');
			}
		},
		setValue: function(value, quiet){
			var r, g, b, a, hexR, hexG, hexB, hashString;
			value = value.trim();
			this.convertRgbToHex = function(color){
				if (m = /^([^0-9]{1,3})*(\d{1,3})[^,]*,([^0-9]{1,3})*(\d{1,3})[^,]*,([^0-9]{1,3})*(\d{1,3})[\s\S]*$/.exec(color)) {
					hexR = m[2] <= 255 ? ("0" + parseInt(m[2], 10).toString(16)).slice(-2) : 'ff';
					hexG = m[4] <= 255 ? ("0" + parseInt(m[4], 10).toString(16)).slice(-2) : 'ff';
					hexB = m[6] <= 255 ? ("0" + parseInt(m[6], 10).toString(16)).slice(-2) : 'ff';
					color = '#' + hexR + hexG + hexB;
					return color;
				}
			};
			// Catch RGB and RGBa
			if ( (m = /^[^,]*,[^,]*,[\s\S]*$/.exec(value)) ) {
				if (m = /^[^,]*(,)[^,]*(,)[^,]*(,)[^.]*(\.|0)[\s\S]*$/.exec(value)) {
					// Catch only RGBa values
					if (m[4] === '.' || m[4] == 0) {
						if (m = /^([^0-9]{1,3})*(\d{1,3})[^,]*,([^0-9]{1,3})*(\d{1,3})[^,]*,([^0-9]{1,3})*(\d{1,3})[^,]*,[^.]*.([^0-9]{1,2})*(\d{1,2})[\s\S]*$/.exec(value)) {
							r = m[2] <= 255 ? m[2] : 255;
							g = m[4] <= 255 ? m[4] : 255;
							b = m[6] <= 255 ? m[6] : 255;
							a = m[8];
							value = 'rgba(' + r + ',' + g + ',' + b + ',0.' + a + ')';
						}
					 } else {
						value = this.convertRgbToHex(value);
					}
				} else {
					value = this.convertRgbToHex(value);
				}
			} else {
				// Check Hex Colors
				if (m = /^\#?[\s\S]*?([a-fA-F0-9]{1,6})[\s\S]*$/.exec(value)) {
					if (value == 'inherit' || value == 'transparent') {
						value = value;
					} else if (m[1].length == 3) {
						value = '#' + m[1][0] + m[1][0] + m[1][1] + m[1][1] + m[1][2] + m[1][2];
					} else if (m[1].length <= 6) {
						hashString = m[1].split('');
						while (hashString.length < 6) {
							hashString.unshift('0');
						}
						value = '#' + hashString.join('');
					}
				}
			}
			if (value == '') {
				this.$preview.removeAttr('style');
				this.$input.removeClass('with_alpha');
			} else {
				if (value == 'inherit' || value == 'transparent') {
					this.$input.removeClass('white');
					this.$preview.css('background', value);
				} else {
					this.$input.colpickSetColor(value);
				}
			}
			this.parentSetValue(value, quiet);
		},
		invertInputColors: function(rgba){
			if ( ! rgba && (typeof rgba != 'object')) return;
			var r = rgba.r ? rgba.r : 0,
				g = rgba.g ? rgba.g : 0,
				b = rgba.b ? rgba.b : 0,
				a = (rgba.a === 0 || rgba.a) ? rgba.a : 1,
				light;
			// Determine lightness of color
			light = r * 0.213 + g * 0.715 + b * 0.072;
			// Increase lightness regarding color opacity
			if (a < 1) {
				light = light + (1 - a) * (1 - light/255) * 235;
			}
			if (light < 178) {
				this.$input.addClass('white');
			} else {
				this.$input.removeClass('white');
			}
		}
	};

	/**
	 * USOF Field: Css / Html
	 */
	$usof.field['css'] = $usof.field['html'] = {

		init: function(){
			this._events = {};
			this._events.editorChange = function(e){
				var value = this.editor.getSession().getValue();
				this.parentSetValue(value);
			}.bind(this);
			this.$editor = this.$row.find('.usof-form-row-control-ace').text(this.getValue());
			// Loading ACE dynamically
			if (window.ace === undefined) {
				this.data = this.$row.find('.usof-form-row-control-param')[0].onclick() || {};
				this.script = document.createElement('script');
				this.extSearchScript = document.createElement('script');
				this.script.onload = this._init.bind(this);
				this.script.type = 'text/javascript';
				this.script.src = this.data.ace_path;
				this.extSearchScript.type = 'text/javascript';
				this.extSearchScript.src = this.data.ace_ext_sarch_path;
				document.getElementsByTagName('head')[0].appendChild(this.script);
				return;
			}
			this._init();
		},

		_init: function(){
			document.getElementsByTagName('head')[0].appendChild(this.extSearchScript);
			this.$input.hide();
			this.editor = ace.edit(this.$editor[0]);
			this.editor.setTheme("ace/theme/usof");
			this.editor.$blockScrolling = Infinity;
			this.editor.getSession().setMode("ace/mode/" + this.type);
			this.editor.setShowFoldWidgets(false);
			this.editor.setFontSize(13);
			this.editor.getSession().setUseWorker(false);
			this.editor.getSession().setValue(this.getValue());
			this.editor.getSession().on('change', this._events.editorChange);
			// Resize handler
			this.$body = $(document.body);
			this.$window = $(window);
			this.$control = this.$row.find('.usof-form-row-control');
			this.$resize = this.$row.find('.usof-form-row-resize').insertAfter(this.$control);
			this.$resizeKnob = this.$row.find('.usof-form-row-resize-knob');
			var startPageY, startHeight, draggedValue;
			$.extend(this._events, {
				dragstart: function(e){
					e.stopPropagation();
					this.$resize.addClass('dragged');
					startPageY = e.pageY;
					startHeight = this.$control.height();
					this.$body.on('mousemove', this._events.dragmove);
					this.$window.on('mouseup', this._events.dragstop);
					this._events.dragmove(e);
				}.bind(this),
				dragmove: function(e){
					e.stopPropagation();
					draggedValue = Math.max(startPageY - startHeight + 400, Math.round(e.pageY));
					this.$resizeKnob.css('top', draggedValue - startPageY);
				}.bind(this),
				dragstop: function(e){
					e.stopPropagation();
					this.$body.off('mousemove', this._events.dragmove);
					this.$window.off('mouseup', this._events.dragstop);
					this.$control.height(startHeight + draggedValue - startPageY);
					this.$resizeKnob.css('top', 0);
					this.editor.resize();
					this.$resize.removeClass('dragged');
				}.bind(this)
			});
			this.$resizeKnob.on('mousedown', this._events.dragstart);
		},

		setValue: function(value){
			if (this.editor !== undefined) {
				this.editor.getSession().off('change', this._events.editorChange);
				this.editor.setValue(value);
				this.editor.getSession().on('change', this._events.editorChange);
			}
			this.parentSetValue(value);
		}

	};

	/**
	 * USOF Field: Select
	 */
	$usof.field['select'] = {
		init: function(options){
			this.parentInit(options);
			this.$select = this.$row.find('select');
			if (this.$select.hasClass('advanced')) {
				this.$select.select2();
			}

			this.$select.on('change', function() {
				this.$row.attr('selected-value', this.$select.val());
			}.bind(this));
			this.$row.attr('selected-value', this.$select.val());
		}
	};

	/**
	 * USOF Field: Grid Layout Select
	 */
	$usof.field['grid_layout_select'] = {
		init: function(options){
			this.parentInit(options);
			this.$select = this.$row.find('select');
			this.$editLink = this.$row.find('.edit-link');
			this.$descEdit = this.$row.find('.us-grid-layout-desc-edit');
			this.$descAdd = this.$row.find('.us-grid-layout-desc-add');
			this.$select.select2();

			this._events = {
				selectChange: this.selectChange.bind(this)
			};

			this.$select.on('change', this._events.selectChange);

			this.selectChange();

		},
		selectChange: function(){
			var $selectedOption = this.$select.find(":selected");
			if ($selectedOption.length && $selectedOption.data('edit-url') != undefined) {
				this.$editLink.attr('href', $selectedOption.data('edit-url'));
				this.$descEdit.show();
				this.$descAdd.hide();
			} else {
				this.$descAdd.show();
				this.$descEdit.hide();
			}
		}
	};

	/**
	 * USOF Field: Font
	 */
	$usof.field['font'] = {

		init: function(options){
			this.parentInit(options);
			this.$select = this.$row.find('select');
			this.$preview = this.$row.find('.usof-font-preview');
			this.$weightsContainer = this.$row.find('.usof-checkbox-list');
			this.$weightCheckboxes = this.$weightsContainer.find('.usof-checkbox');
			this.$weights = this.$weightsContainer.find('input');
			this.fonts = $('.usof-fonts-json')[0].onclick() || {};
			this.fontStyleFields = this.$row.find('.usof-font-style-fields-json')[0].onclick() || {};
			this.curFont = this.$select.find(':selected').val();
			this.isCurFontUploaded = ( this.$select.find(':selected').length && this.$select.find(':selected').hasClass('uploaded'));
			this.notLoadedFonts = {};

			this.$select.find('.websafe, .uploaded').each(function(index, option){
				var $option = $(option),
					value = $option.attr('value');
				this.notLoadedFonts[value] = value;
			}.bind(this));

			this.$select.on('change', function(){
				this.isCurFontUploaded = ( this.$select.find(':selected').length && this.$select.find(':selected').hasClass('uploaded'));
				this.setValue(this._getValue());
			}.bind(this));
			this.$weights.on('change', function(){
				this.setValue(this._getValue());
			}.bind(this));
			if (this.curFont != 'none' && this.notLoadedFonts[this.curFont] == undefined) {
				$('head').append('<link href="//fonts.googleapis.com/css?family=' + this.curFont.replace(/\s+/g, '+') + '" rel="stylesheet" class="usof_font_' + this.id + '" />');
				this.$preview.css('font-family', this.curFont + '');
			} else if (this.curFont != 'none' && this.notLoadedFonts[this.curFont] != undefined) {
				this.$preview.css('font-family', this.curFont + '');
			}
			this.$select.select2();

			if (this.fontStyleFields.colorField != undefined) {
				$usof.instance.fields[this.fontStyleFields.colorField].on('change', function(){
					this.$preview.css('color', $usof.instance.fields[this.fontStyleFields.colorField].getValue());
				}.bind(this));
			}
			if (this.fontStyleFields.sizeField != undefined) {
				$usof.instance.fields[this.fontStyleFields.sizeField].on('change', function(){
					this.$preview.css('font-size', $usof.instance.fields[this.fontStyleFields.sizeField].getValue()+'px');
				}.bind(this));
			}
			if (this.fontStyleFields.lineheightField != undefined) {
				$usof.instance.fields[this.fontStyleFields.lineheightField].on('change', function(){
					this.$preview.css('line-height', $usof.instance.fields[this.fontStyleFields.lineheightField].getValue()+'px');
				}.bind(this));
			}
			if (this.fontStyleFields.weightField != undefined) {
				$usof.instance.fields[this.fontStyleFields.weightField].on('change', function(){
					this.$preview.css('font-weight', $usof.instance.fields[this.fontStyleFields.weightField].getValue());
				}.bind(this));
			}
			if (this.fontStyleFields.letterspacingField != undefined) {
				$usof.instance.fields[this.fontStyleFields.letterspacingField].on('change', function(){
					this.$preview.css('letter-spacing', $usof.instance.fields[this.fontStyleFields.letterspacingField].getValue()+'em');
				}.bind(this));
			}
			if (this.fontStyleFields.transformField != undefined) {
				$usof.instance.fields[this.fontStyleFields.transformField].on('change', function(){
					if ($usof.instance.fields[this.fontStyleFields.transformField].getValue().indexOf("uppercase") != -1) {
						this.$preview.css('text-transform', 'uppercase');
					} else {
						this.$preview.css('text-transform', '');
					}
					if ($usof.instance.fields[this.fontStyleFields.transformField].getValue().indexOf("italic") != -1) {
						this.$preview.css('font-style', 'italic');
					} else {
						this.$preview.css('font-style', '');
					}
				}.bind(this));
			}
		},

		setValue: function(value, quiet){
			var parts = value.split('|'),
				fontName = parts[0] || 'none',
				fontWeights = parts[1] || '400,700';
			fontWeights = fontWeights.split(',');
			if (fontName != this.curFont) {
				$('.usof_font_' + this.id).remove();
				if (fontName == 'none') {
					// Selected no-font
					this.$preview.css('font-family', '');
				}
				else if (this.notLoadedFonts[fontName] != undefined) {
					// Web-safe font combination and uploaded fonts
					this.$preview.css('font-family', fontName);
				}
				else {
					// Selected some google font: show preview
					$('head').append('<link href="//fonts.googleapis.com/css?family=' + fontName.replace(/\s+/g, '+') + '" rel="stylesheet" class="usof_font_' + this.id + '" />');
					this.$preview.css('font-family', fontName + ', sans-serif');
				}
				if (this.$select.select2('val') != fontName) {
					// setValue may be called both from inside and outside, so checking to avoid recursion
					this.$select.select2('val', fontName);
				}
				this.curFont = fontName;
			}
			if (this.fontStyleFields.weightField == undefined) {
				if (fontWeights.length == 0) {
					this.$preview.css('font-weight', '');
				} else {
					this.$preview.css('font-weight', parseInt(fontWeights[0]));
				}
			}
			// Show the available weights
			if (this.fonts[fontName] === undefined || this.isCurFontUploaded) {
				this.$weightCheckboxes.addClass('hidden');
			} else {
				this.$weightCheckboxes.each(function(index, elm){
					var $elm = $(elm),
						weightValue = $elm.data('value') + '';
					$elm.toggleClass('hidden', $.inArray(weightValue, this.fonts[fontName].variants) == -1);
					$elm.attr('checked', ($.inArray(weightValue, fontWeights) == -1) ? 'checked' : false);
				}.bind(this));
			}
			this.parentSetValue(value, quiet);
		},

		_getValue: function(){
			var fontName = this.$select.val(),
				fontWeights = [];
			if (this.fonts[fontName] !== undefined && this.fonts[fontName].variants !== undefined) {
				this.$weights.filter(':checked').each(function(index, elm){
					var weightValue = $(elm).val() + '';
					if ($.inArray(weightValue, this.fonts[fontName].variants) != -1) {
						fontWeights.push(weightValue);
					}
				}.bind(this));
			}
			return fontName + '|' + fontWeights.join(',');
		}

	};

	/**
	 * USOF Field: Imgradio / Radio
	 */
	$usof.field['imgradio'] = $usof.field['radio'] = {

		getValue: function(){
			return this.$input.filter(':checked').val();
		},

		setValue: function(value, quiet){
			if (quiet) this.$input.off('change', this._events.change);
			this.$input.filter('[value="' + value + '"]').attr('checked', 'checked');
			if (quiet) this.$input.on('change', this._events.change);
		}

	};

	/**
	 * USOF Field: Link
	 */
	$usof.field['link'] = {

		init: function(options){
			this.parentInit(options);
			this.$mainField = this.$row.find('input[type="hidden"]:first');
			this.$url = this.$row.find('input[type="text"]:first');
			this.$target = this.$row.find('input[type="checkbox"]:first');

			this.$url.on('change', function(){
				this.$mainField.val(JSON.stringify(this.getValue()));
				this.trigger('change', this.getValue());
			}.bind(this));

			this.$target.on('change', function(){
				this.$mainField.val(JSON.stringify(this.getValue()));
				this.trigger('change', this.getValue());
			}.bind(this));
		},

		getValue: function(){
			if (!this.inited) return {};
			return {
				url: this.$url.val(),
				target: this.$target.is(':checked') ? '_blank' : ''
			};
		},

		setValue: function(value, quiet){
			if (!this.inited) return;
			if (typeof value != 'object' || value.url === undefined) {
				value = {
					url: (typeof value == 'string') ? value : ''
				}
			}
			this.$url.val(value.url);
			this.$target.attr('checked', (value.target == '_blank') ? 'checked' : false);
		},

	};

	/**
	 * USOF Field: Reset
	 */
	$usof.field['reset'] = {

		init: function(){
			this.$btnReset = this.$row.find('.usof-button.type_reset').on('click', this.reset.bind(this));
			this.resetStateTimer = null;
			this.i18n = (this.$row.find('.usof-form-row-control-i18n')[0].onclick() || {});
		},

		reset: function(){
			if (!confirm(this.i18n.reset_confirm)) return;
			clearTimeout(this.resetStateTimer);
			this.$btnReset.addClass('loading');
			$.ajax({
				type: 'POST',
				url: $usof.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_reset',
					_wpnonce: $usof.instance.$container.find('[name="_wpnonce"]').val(),
					_wp_http_referer: $usof.instance.$container.find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					this.$btnReset.removeClass('loading');
					alert(this.i18n.reset_complete);
					location.reload();
				}.bind(this)
			});
		}

	};

	/**
	 * USOF Field: Icon
	 */
	$usof.field['icon'] = {

		init: function(options){
			this.$value = this.$row.find('.us-icon-value');
			this.$select = this.$row.find('.us-icon-select');
			this.$text = this.$row.find('.us-icon-text');
			this.$preview = this.$row.find('.us-icon-preview > i');
			this.$setLink = this.$row.find('.us-icon-set-link');

			this.$select.on('change', function(){
				var $selectedOption = this.$select.find(":selected");
				if ($selectedOption.length) {
					this.$setLink.attr('href', $selectedOption.data('info-url'));
				}
				this.setIconValue();
			}.bind(this));

			this.$text.on('change keyup', function(){
				var val = this.$text.val();
				if (val.toLowerCase().replace(/^\s+/g, '') !== val) {
					this.$text.val($.trim(val.toLowerCase()));
				}
				this.setIconValue();
			}.bind(this));

		},
		setIconValue: function(){
			var icon_set = this.$select.val(),
				icon_name = $.trim(this.$text.val()),
				icon_val = '';
			if ( icon_name != '' ) {
				if ( icon_set == 'material' ) {
					icon_name = icon_name.replace(/ +/g, '_');
				}
				icon_val = icon_set+'|'+icon_name;
			}
			this.renderPreview(icon_set, icon_name);
			this.$value.val(icon_val);
		},
		renderValue: function(value){
			var $selectedOption;
			value = value.trim().split('|');
			if (value.length != 2) {
				$selectedOption = this.$select.find('option:first');
				this.$text.val('');
			} else {
				value[0] = value[0].toLowerCase();
				$selectedOption = this.$select.find('option[value="'+value[0]+'"]');
				this.$text.val(value[1]);
			}
			if ($selectedOption.length) {
				this.$select.find('option').prop('selected', false);
				$selectedOption.prop('selected', 'selected');
			}

			this.renderPreview(value[0], value[1]);
		},
		renderPreview: function(icon_set, icon_name){
			if ( icon_name != '') {
				if ( icon_set == 'material' ) {
					this.$preview.attr('class', 'material-icons').html(icon_name);
				} else {
					if(icon_name != undefined){
						icon_name = icon_name.replace(/fa-\dx/gi, ' ');
					}
					this.$preview.attr('class', icon_set+' fa-'+icon_name).html('');
				}
			} else {
				this.$preview.attr('class', 'material-icons').html('');
			}
		},
		setValue: function(value, quiet){
			this.renderValue(value);
			this.parentSetValue(value, quiet);
		}
	};

	/**
	 * USOF Field: Slider
	 */
	$usof.field['slider'] = {

		init: function(options){
			this.$slider = this.$row.find('.usof-slider');
			// Params
			this.min = parseFloat(this.$slider.data('min'));
			this.max = parseFloat(this.$slider.data('max'));
			this.step = parseFloat(this.$slider.data('step')) || 1;
			this.prefix = this.$slider.data('prefix') || '';
			this.postfix = this.$slider.data('postfix') || '';
			this.$textfield = this.$row.find('input[type="text"]');
			this.$box = this.$row.find('.usof-slider-box');
			this.$range = this.$row.find('.usof-slider-range');
			this.$body = $(document.body);
			this.$window = $(window);
			this.$usofContainer = $('.usof-container');
			// Needed box dimensions
			this.sz = {};
			var draggedValue;
			this._events = {
				dragstart: function(e){
					e.stopPropagation();
					this.$usofContainer.addClass('dragged');
					this.$box.addClass('dragged');
					this.sz = {left: this.$box.offset().left, right: this.$box.offset().left + this.$box.width(), width: this.$box.width()};
					this.$body.on('mousemove', this._events.dragmove);
					this.$window.on('mouseup', this._events.dragstop);
					this._events.dragmove(e);
				}.bind(this),
				dragmove: function(e){
					e.stopPropagation();
					var x, value;
					if (this.$body.hasClass('rtl')) {
						x = Math.max(0, Math.min(1, (this.sz == 0) ? 0 : ((this.sz.right - e.pageX) / this.sz.width)));
					} else {
						x = Math.max(0, Math.min(1, (this.sz == 0) ? 0 : ((e.pageX - this.sz.left) / this.sz.width)))
					}
					value = parseFloat(this.min + x * (this.max - this.min));
					value = Math.round(value / this.step) * this.step;
					this.renderValue(value);
					draggedValue = value;
				}.bind(this),
				dragstop: function(e){
					e.preventDefault();
					e.stopPropagation();
					this.$usofContainer.removeClass('dragged');
					this.$box.removeClass('dragged');
					this.$body.off('mousemove', this._events.dragmove);
					this.$window.off('mouseup', this._events.dragstop);
					this.setValue(draggedValue);
				}.bind(this),
				mousewheel: function(e){
					e.preventDefault();
					e.stopPropagation();
					var direction;
					if (e.type == 'mousewheel') {
						direction = e.originalEvent.wheelDelta;
					}
					else if (e.type == 'DOMMouseScroll') {
						direction = -e.originalEvent.detail;
					}
					if(direction > 0) {
						var value = Math.min(this.max, parseFloat(this.getValue()) + this.step);
					}
					else{
						var value = Math.max(this.min, parseFloat(this.getValue()) - this.step);
					}
					value = Math.round(value / this.step) * this.step;
					this.setValue(value);
				}.bind(this),
				mouseenter: function(e){
					this.$window.on('mousewheel DOMMouseScroll', this._events.mousewheel);
				}.bind(this),
				mouseleave: function(e){
					this.$window.off('mousewheel DOMMouseScroll', this._events.mousewheel);
				}.bind(this)
			};
			this.$textfield.on('focus', function(){
				this.$textfield.val(this.getValue());
				this.oldTextFieldValue = this.getValue();
			}.bind(this));
			this.$textfield.on('blur', function(){
				var value = parseFloat(this.$textfield.val().replace('[^0-9.]+', ''));
				if ((value || parseFloat(value) === 0) && value != this.oldTextFieldValue) {
					this.setValue(value);
				} else {
					this.renderValue(this.oldTextFieldValue);
				}
			}.bind(this));
			this.$box.on('mousedown', this._events.dragstart);
			this.$textfield.on('mouseenter', this._events.mouseenter);
			this.$textfield.on('mouseleave', this._events.mouseleave);
		},

		renderValue: function(value){
			var x = Math.max(0, Math.min(1, (value - this.min) / (this.max - this.min))),
				valueDecimalPart;
			if (this.$body.hasClass('rtl')) {
				this.$range.css('right', x * 100 + '%');
			} else {
				this.$range.css('left', x * 100 + '%');
			}


			if ($.isNumeric(value)) {
				value = parseFloat(value);
				valueDecimalPart = Math.abs(value) % 1 + '';
				if (valueDecimalPart.charAt(3) !== '' && valueDecimalPart.charAt(3) !== '0') { // Decimal part has 1/100 part
					value = value.toFixed(2);
				} else if (valueDecimalPart.charAt(2) !== '' && valueDecimalPart.charAt(2) !== '0') { // Decimal part has 1/10 part
					value = value.toFixed(1);
				} else { // Decimal part is less than 1/100 or it is just 0
					value = value.toFixed(0);
				}
			}

			this.$textfield.val(this.prefix + value + this.postfix);
		},

		setValue: function(value, quiet){
			this.renderValue(value);
			this.parentSetValue(value, quiet);
		}

	};

	/**
	 * USOF Field: Switch
	 */
	$usof.field['switch'] = {

		getValue: function(){
			return (this.$input.is(':checked') ? this.$input.get(1).value : this.$input.get(0).value);
		},

		setValue: function(value, quiet){
			if (typeof value == 'string') value = (value == 'true' || value == 'True' || value == 'TRUE' || value == '1');
			else if (typeof value != 'boolean') value = !!parseInt(value);
			this.$input.filter('[type="checkbox"]').prop('checked', value);
		}

	};

	/**
	 * USOF Field: Text / Textarea
	 */
	$usof.field['text'] = $usof.field['textarea'] = {

		init: function(){
			this.$input.on('change keyup', function(){
				this.trigger('change', this.getValue());
			}.bind(this));
		}

	};

	/**
	 * USOF Field: Button Preview
	 */
	$usof.field['button_preview'] = {

		init: function(){

			this.dependsOn = ['color_content_primary','button_fontsize','button_fontweight','button_height','button_width','button_border_radius','button_letterspacing','button_shadow','button_text_style','button_font','button_hover','heading_font_family','body_font_family','menu_font_family'];
			this.$buttons = this.$row.find('.usof-button-example');
			this.$buttonsBefore = this.$row.find('.usof-button-example-before');
			this.$buttonsPreview = this.$row.find('.usof-button-preview');

			for (var fieldId in $usof.instance.fields) {
				if (!$usof.instance.fields.hasOwnProperty(fieldId)) continue;
				if ($.inArray($usof.instance.fields[fieldId].name, this.dependsOn) === -1) continue;
				$usof.instance.fields[fieldId].on('change', function(field, value){
					this.applyStyle();
				}.bind(this));
			}
			this.applyStyle();
		},
		applyStyle: function() {
			// Colors
			this.$buttons.css('background-color', $usof.instance.getValue('color_content_primary'));
			this.$buttons.css('border-color', $usof.instance.getValue('color_content_primary'));
			this.$buttons.css('color', $usof.instance.getValue('color_content_primary'));
			this.$buttonsBefore.css('background-color', $usof.instance.getValue('color_content_primary'));
			// Hover class
			this.$buttonsPreview.usMod('hov', $usof.instance.getValue('button_hover'));
			// Font family
			var buttonFont = $usof.instance.getValue('button_font'),
				fontFamily;
			if ( buttonFont == 'heading' || buttonFont == 'body' ) {
				fontFamily = $usof.instance.getValue($usof.instance.getValue('button_font')+'_font_family').split('|')[0];
			} else {
				fontFamily = buttonFont;
			}
			if (fontFamily == 'none') {
				fontFamily = '';
			}
			this.$buttons.css('font-family', fontFamily);
			// Text style
			if ($.inArray('italic', $usof.instance.getValue('button_text_style')) !== -1) {
				this.$buttons.css('font-style', 'italic');
			} else {
				this.$buttons.css('font-style', 'normal');
			}
			if ($.inArray('uppercase', $usof.instance.getValue('button_text_style')) !== -1) {
				this.$buttons.css('text-transform', 'uppercase');
			} else {
				this.$buttons.css('text-transform', 'none');
			}
			// Font size
			this.$buttons.css('font-size', $usof.instance.getValue('button_fontsize')+'px');
			// Font weight
			this.$buttons.css('font-weight', $usof.instance.getValue('button_fontweight'));
			// Height
			this.$buttons.css('line-height', $usof.instance.getValue('button_height'));
			// Width
			this.$buttons.css('padding', '0 '+$usof.instance.getValue('button_width')+'em');
			// Border radius
			this.$buttons.css('border-radius', $usof.instance.getValue('button_border_radius')+'em');
			// Letter spacing
			this.$buttons.css('letter-spacing', $usof.instance.getValue('button_letterspacing')+'em');
			// Shadow
			this.$buttons.css('box-shadow', '0 '+$usof.instance.getValue('button_shadow')/2+'em '+$usof.instance.getValue('button_shadow')+'em rgba(0,0,0,0.2)');
			// Hovered state
			this.$buttons.off('mouseenter mouseleave').on('mouseenter', function() {
				var $this = $(this),
					$before = $this.find('.usof-button-example-before');
				$this.css('box-shadow', '0 '+$usof.instance.getValue('button_shadow_hover')/2+'em '+$usof.instance.getValue('button_shadow_hover')+'em rgba(0,0,0,0.2)');
				if ($usof.instance.getValue('button_hover') == 'reverse') {
					if ($this.hasClass('style_solid')) {
						$this.css('background-color', 'transparent');
						$this.css('color', '');
						$this.attr('style', function(i,s) { return s + 'color: '+$usof.instance.getValue('color_content_primary')+' !important;' });
					} else if ($this.hasClass('style_outlined')) {
						$this.css('background-color', '');
						$this.css('color', '#fff');
						$this.attr('style', function(i,s) { return s + 'background-color: '+$usof.instance.getValue('color_content_primary')+' !important;' });
					}
				}
			}).on('mouseleave', function() {
				var $this = $(this),
					$before = $this.find('.usof-button-example-before');
				$this.css('box-shadow', '0 '+$usof.instance.getValue('button_shadow')/2+'em '+$usof.instance.getValue('button_shadow')+'em rgba(0,0,0,0.2)');
				if ($usof.instance.getValue('button_hover') == 'reverse') {
					$this.css('background-color', $usof.instance.getValue('color_content_primary'));
					$this.css('border-color', $usof.instance.getValue('color_content_primary'));
					$this.css('color', $usof.instance.getValue('color_content_primary'));
					$before.css('background-color', $usof.instance.getValue('color_content_primary'));
				}
			});

		}

	};

	/**
	 * USOF Field: Transfer
	 */
	$usof.field['transfer'] = {

		init: function(){
			if ($usof.instance.$sections['headerbuilder'] !== undefined) {
				$usof.instance.fireFieldEvent($usof.instance.$sections['headerbuilder'], 'beforeShow');
				$usof.instance.fireFieldEvent($usof.instance.$sections['headerbuilder'], 'beforeHide');
			}

			this.$textarea = this.$row.find('textarea');
			this.translations = (this.$row.find('.usof-transfer-translations')[0].onclick() || {});
			this.$btnImport = this.$row.find('.usof-button.type_import').on('click', this.importValues.bind(this));

			this.hiddenFieldsValues = $('.usof-hidden-fields')[0].onclick() || {};
			// If there are no hidden values (array length == 0), hiddenFieldsValues JSON equals [] isntead of {}, but wee need {}, so we can extend values of options correctly
			if (this.hiddenFieldsValues.length == 0) {
				this.hiddenFieldsValues = {};
			}

			this.exportValues();
			this.on('beforeShow', this.exportValues.bind(this));
		},

		exportValues: function(){
			var values = $.extend(this.hiddenFieldsValues, $usof.instance.getValues());
			this.$textarea.val(JSON.stringify(values));
		},

		importValues: function(){
			var encoded = this.$textarea.val(),
				values;

			if (encoded.charAt(0) == '{') {
				this.$btnImport.addClass('loading');
				// New USOF export: json-encoded
				$.ajax({
					type: 'POST',
					url: $usof.ajaxUrl,
					dataType: 'json',
					data: {
						action: 'usof_save',
						usof_options: encoded,
						_wpnonce: $usof.instance.$container.find('[name="_wpnonce"]').val(),
						_wp_http_referer: $usof.instance.$container.find('[name="_wp_http_referer"]').val()
					},
					success: function(result){
						this.$btnImport.removeClass('loading');
						if (result.success) {
							alert(this.translations.importSuccess);
							location.reload();
						} else {
							alert(result.data.message);
						}
					}.bind(this)
				});
			} else {
				try{
					// Old SMOF export: base64-encoded
					var serialized = window.atob(encoded),
						matches = serialized.match(/(s\:[0-9]+\:\"(.*?)\"\;)|(i\:[0-9]+\;)/g),
						_key = null,
						_value;
					values = {};
					for (var i = 0; i < matches.length; i++) {
						_value = matches[i].replace((matches[i].charAt(0) == 's') ? /^s\:[0-9]+\:\"(.*?)\"\;$/ : /^i\:([0-9]+)\;$/, '$1');
						if (_key === null) {
							_key = _value;
						} else {
							values[_key] = _value;
							_key = null;
						}
					}
					$usof.instance.setValues(values);
					this.valuesChanged = values;
					$usof.instance.save();
				} catch (error) {
					return alert(this.translations.importError);
				}

			}

		}

	};

	/**
	 * USOF Field: Style Scheme
	 */
	$usof.field['style_scheme'] = {

		init: function(options){
			this.$input = this.$row.find('input[name="'+this.name+'"]');
			this.$schemesContainer = this.$row.find('.usof-schemes-list');
			this.$schemeItems = this.$row.find('.usof-schemes-list > li');
			this.$nameInput = this.$row.find('#style_scheme_name');
			this.$saveNewBtn = this.$row.find('#save_new_style_scheme').on('click', this.saveScheme.bind(this));
			this.$saveCurrentBtn = this.$row.find('#save_current_style_scheme').on('click', this.saveScheme.bind(this));
			this.deleteButtonTitle = $(this.$row.find('.usof-schemes-item.type_custom .usof-schemes-item-delete')[0]).attr('title');
			this.schemes = (this.$row.find('.usof-form-row-control-schemes-json')[0].onclick() || {});
			this.customSchemes = (this.$row.find('.usof-form-row-control-custom-schemes-json')[0].onclick() || {});
			this.colors = (this.$row.find('.usof-form-row-control-colors-json')[0].onclick() || {});
			this.i18n = (this.$row.find('.usof-form-row-control-i18n')[0].onclick() || {});
			this.$nameInput.on('keyup', this.setSchemeButtonStates.bind(this));
			this.initSchemes();
		},
		setSchemeButtonStates: function(action){
			if ( ! this.$schemesContainer) return;
			var isCustom = this.$schemesContainer.find('.active').hasClass('type_custom');
			if (action == 'color') this.isColorChanged = true;
			if (this.$nameInput.val().length) {
				this.$saveNewBtn.removeAttr('disabled');
			} else {
				this.$saveNewBtn.attr('disabled', '');
			}
			if (this.isColorChanged && isCustom) {
				this.$saveCurrentBtn.removeAttr('disabled');
			} else {
				this.$saveCurrentBtn.attr('disabled', '');
			}
		},
		initSchemes: function(){
			this.$schemeItems.each(function(index, item){
				var $item = $(item),
					schemeId = $item.data('id'),
					$deleteBtn = $item.find('.usof-schemes-item-delete'),
					isCustom = $item.hasClass('type_custom'),
					colors;
				$deleteBtn.attr('title', this.deleteButtonTitle);
				$item.on('click', function(){
					if (window.$usof !== undefined && $usof.instance !== undefined) {
						if (( ! isCustom && this.schemes[schemeId] === undefined) || (isCustom && this.customSchemes[schemeId] === undefined) || ( ! isCustom && this.schemes[schemeId].values === undefined) || (isCustom && this.customSchemes[schemeId].values === undefined)) return;
						this.$schemeItems.removeClass('active');
						this.isColorChanged = null;
						this.setSchemeButtonStates();
						$item.addClass('active');
						if (isCustom) {
							colors = this.customSchemes[schemeId].values;
							this.$input.val('custom-'+schemeId);
							this.trigger('change', 'custom-'+schemeId);
						} else {
							colors = this.schemes[schemeId].values;
							this.$input.val(schemeId);
							this.trigger('change', schemeId);
						}
						$.each(colors, function(id, value){
							$usof.instance.setValue(id, value);
						});
					}
				}.bind(this));
				if ($deleteBtn.length) {
					$deleteBtn.on('click', function(event){
						this.deleteScheme(schemeId, event);
					}.bind(this));
				}
			}.bind(this));
		},
		getColorValues: function(){
			var colors = {};
			if (window.$usof === undefined || $usof.instance == undefined) {
				return undefined;
			}
			if (this.colors == undefined) {
				return undefined;
			}
			$.each(this.colors, function(id, color){
				colors[color] = $usof.instance.getValue(color);
			});

			return colors;
		},
		saveScheme: function(event){
			var colors = this.getColorValues(),
				name = this.$nameInput.val(),
				scheme = {name: name, colors: colors},
				$activeScheme = this.$schemeItems.filter('.active'),
				$button = $(event.target.closest('button'));
			if (name == '' || (name.length && this.$saveCurrentBtn[0] == $button[0])) {
				if ($activeScheme.hasClass('type_custom')) {
					scheme.name = $activeScheme.find('.preview_text').html();
					scheme.id = $activeScheme.data('id');
					$activeScheme.addClass('saved');
				} else {
					return false;
				}
			}
			$button.addClass('loading');
			$.ajax({
				type: 'POST',
				url: $usof.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_save_style_scheme',
					scheme: JSON.stringify(scheme),
					_wpnonce: this.$row.closest('.usof-form').find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$row.closest('.usof-form').find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					this.setSchemes(result.data.schemes, result.data.customSchemes, result.data.schemesHtml);
					this.$nameInput.val('');
					$button.removeClass('loading');
					this.$saveNewBtn.attr('disabled', '');
					this.$saveCurrentBtn.attr('disabled', '');
					this.isColorChanged = null;
				}.bind(this)
			});
			return false;
		},
		deleteScheme: function(schemeId, event){
			var $deletingScheme = this.$row.find('.usof-schemes-list > li.type_custom[data-id="' + schemeId + '"]')[0];
			event.stopPropagation();
			if (!confirm(this.i18n.delete_confirm)) return false;
			if ($deletingScheme) $($deletingScheme).addClass('deleted');

			$.ajax({
				type: 'POST',
				url: $usof.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_delete_style_scheme',
					scheme: schemeId,
					_wpnonce: this.$row.closest('.usof-form').find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$row.closest('.usof-form').find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					this.setSchemes(result.data.schemes, result.data.customSchemes, result.data.schemesHtml);
					this.$saveNewBtn.attr('disabled', '');
					this.$saveCurrentBtn.attr('disabled', '');
					this.isColorChanged = null;
				}.bind(this)
			});
			return false;
		},
		setSchemes: function(schemes, customSchemes, schemesHtml){
			var $activeScheme = this.$schemeItems.filter('.active'),
				activeSchemeId, isActiveCustom;
			if ($activeScheme.length) {
				activeSchemeId = $activeScheme.data('id');
				isActiveCustom = $activeScheme.hasClass('type_custom');
			}
			this.schemes = schemes;
			this.customSchemes = customSchemes;
			this.$schemesContainer.html(schemesHtml);
			this.$schemeItems = this.$row.find('.usof-schemes-list > li');
			this.initSchemes();

			if (activeSchemeId !== undefined) {
				this.$schemeItems.filter('[data-id="'+activeSchemeId+'"]').each(function(index, item) {
					var $item = $(item);
					if ((isActiveCustom && $item.hasClass('type_custom')) || ( ! isActiveCustom && ! $item.hasClass('type_custom'))) {
						$item.addClass('active');
					}
				});
			}
		}
	};

	/**
	 * USOF Field: Upload
	 */
	$usof.field['upload'] = {

		init: function(options){
			this.parentInit(options);
			// Cached URLs for certain values (images IDs and sizes)
			this.$upload = this.$row.find('.usof-upload');
			this.previewType = this.$upload.usMod('preview');
			this.isMultiple = this.$upload.hasClass('is_multiple'); // works for text preview only at the moment
			this.$btnSet = this.$row.find('.usof-button.type_upload');
			this.$btnRemove = this.$row.find('.usof-button.type_remove');
			this.$previewContainer = this.$row.find('.usof-upload-preview');
			if (this.previewType == 'image') {
				this.$previewImg = this.$previewContainer.find('img');
			} else if (this.previewType == 'text') {
				this.$fontName = this.$previewContainer.find('.usof-upload-file');
			}
			this.$btnSet.add(this.$row.find('.usof-button.type_change')).on('click', this.openMediaUploader.bind(this));
			this.$btnRemove.on('click', function(){
				this.setValue('');
			}.bind(this));
		},

		setValue: function(value, quiet){
			if (value == '') {
				// Removed value
				this.$previewContainer.hide();
				this.$btnSet.show();
				if (this.previewType == 'image') {
					this.$previewImg.attr('src', '');
				} else if (this.previewType == 'text') {
					this.$fontName.html('');
				}
			} else {
				var files;
				if ( ! this.isMultiple) {
					files = [value];
				} else {
					files = value;
				}
				if (this.previewType == 'text') {
					this.$fontName.html('');
				}
				$.each(files, function(index, file){
					if (file.match(/^[0-9]+(\|[a-z_\-0-9]+)?$/)) {
						var attachment = wp.media.attachment(parseInt(file)),
							renderAttachmentImage = function(){
								var src = attachment.attributes.url;
								if (attachment.attributes.sizes !== undefined) {
									var size = (attachment.attributes.sizes.medium !== undefined) ? 'medium' : 'full';
									src = attachment.attributes.sizes[size].url;
								}
								if (this.previewType == 'image') {
									this.$previewImg.attr('src', src);
								} else if (this.previewType == 'text') {
									var html = this.$fontName.html() + '<span>' + this._baseName(src) + '</span>';
									this.$fontName.html(html);
								}
							}.bind(this);
						if (attachment.attributes.url !== undefined) {
							renderAttachmentImage();
						} else {
							// Loading missing data via ajax
							attachment.fetch({success: renderAttachmentImage});
						}
					} else {
						// Direct image URL (for old SMOF framework compatibility)
						if (this.previewType == 'image') {
							this.$previewImg.attr('src', file);
						} else if (this.previewType == 'text') {
							this.$fontName.html(this._baseName(file));
						}
					}
				}.bind(this));

				this.$previewContainer.show();
				this.$btnSet.hide();
			}
			this.parentSetValue(value, quiet);
		},

		openMediaUploader: function(){
			if (this.frame === undefined) {
				var mediaSettings = {
					title: this.$btnSet.text(),
					multiple: false,
					button: {text: this.$btnSet.text()}
				};
				if (this.previewType == 'image') {
					mediaSettings.library ={type: 'image'};
				}
				if (this.isMultiple) {
					mediaSettings.multiple = 'add';
				}
				this.frame = wp.media(mediaSettings);
				this.frame.on('open', function(){
					var value;
					if (this.isMultiple) {
						value = this.getValue().trim().split(',');
						$.each(value, function(index, file){
							var fileID = parseInt(file);
							if (fileID) this.frame.state().get('selection').add(wp.media.attachment(fileID));
						}.bind(this));
					} else {
						value = parseInt(this.getValue());
						if (value) this.frame.state().get('selection').add(wp.media.attachment(value));
					}


				}.bind(this));
				this.frame.on('select', function(){
					if (this.isMultiple) {
						var attachments = [];
						this.frame.state().get('selection').each(function(attachment, index){
							attachments.push(attachment.id + '|full');
						});
						this.setValue(attachments);
					} else {
						var attachment = this.frame.state().get('selection').first();
						this.setValue(attachment.id + '|full');
					}

				}.bind(this));
			}
			this.frame.open();
		},

		_baseName: function(src){
			var base = new String(src).substring(src.lastIndexOf('/') + 1);
			return base;
		}

};

	/**
	 * USOF Field: Design Options
	 */
	$usof.field['design_options'] = {
		init: function(options){
			this.$input = this.$row.find('input[type="text"]');
			this.parentInit(options);
			this.$input.on('blur', function(e){
				var $target = $(e.target),
					rawValue = $target.val(),
					cleanValue = this._cleanValue(rawValue);
				if (rawValue != '' && rawValue != cleanValue) {
					$target.val(cleanValue);
					this._events.change();
				}
			}.bind(this));

			// Position switchers
			this.$posSwitcher = {};
			this.$posWrapper = {};
			this.$posInputs = {};
			this.$row.find('input[type="checkbox"][name^="pos_abs_"]').each(function(_, posSwitcher){
				var $posSwitcher = $(posSwitcher),
					$posControl = $posSwitcher.closest('.usof-design-control'),
					state = $posControl.usMod('for');
				this.$posSwitcher[state] = $posSwitcher;
				this.$posWrapper[state] = $posControl.children('.usof-design-position');
				this.$posInputs[state] = $posControl.find('input[name^="position_"]');
				$posSwitcher.on('change', function(e){
					this.$posWrapper[state].toggleClass('pos_off', !$posSwitcher.is(':checked'));
					if (!$posSwitcher.is(':checked')) {
						// Clearing all values
						this.$posInputs[state].val('');
					}
					else {
						// Focusing on the first position control
						this.$posInputs[state].val('0').first().focus();
					}
				}.bind(this));
			}.bind(this));

		},
		_cleanValue: function(rawValue){
			var regexp = /^([\-0-9\.]+)(em|rem|px|\%)$/.exec(rawValue);
			if (rawValue === '' || rawValue === '0') {
				// '' / '0'
				return rawValue;
			} else if (regexp && regexp[2] === 'px') {
				// '12px'
				return parseInt(regexp[1]) + regexp[2];
			} else if (regexp) {
				// '12em' / '12.1em' / '12rem' / '12.1rem' / '12%' / '12.1%'
				return parseFloat(regexp[1]) + regexp[2];
			} else {
				// '12'
				return parseInt(rawValue) + 'px';
			}
		},
		getValue: function(){
			var value = {};
			this.$input.each(function(index, input){
				var $input = $(input),
					name = $input.attr('name'),
					rawValue = $input.val();
				value[name] = this._cleanValue(rawValue);
			}.bind(this));
			return value;
		},
		setValue: function(value){
			var checkedPosSwitchers = [],
				regexp;
			this.$input.each(function(index, input){
				var $input = $(input),
					name = $input.attr('name'),
					inputValue = (value[name] === undefined) ? '' : value[name];
				$input.val(inputValue);
				// If got at least one filled value, will be enabled
				if (inputValue !== '' && (regexp = /^position\_[a-z]+\_(.+)$/.exec(name))) checkedPosSwitchers.push(regexp[1]);
			}.bind(this));
			// Enabling/disabling position switchers depending on values
			$.each(this.$posSwitcher, function(state, $posSwitcher){
				var shouldBeChecked = (checkedPosSwitchers.indexOf(state) !== -1);
				$posSwitcher.prop('checked', shouldBeChecked);
				this.$posWrapper[state].toggleClass('pos_off', !shouldBeChecked);
			}.bind(this));
		}
	};

	/**
	 * Field initialization
	 *
	 * @param options object
	 * @returns {$usof.field}
	 */
	$.fn.usofField = function(options){
		return new $usof.field(this, options);
	};

	/**
	 * USOF Group
	 */
	$usof.Group = function(row, options){
		this.init(row, options);
	};

	$usof.Group.prototype = {

		init: function(elm, options){
			this.$field = $(elm);
			this.$btnAddGroup = this.$field.find('.usof-form-group-add');
			this.$btnDelGroup = this.$field.find('.usof-control-delete');
			this.$groupPrototype = this.$field.find('.usof-form-group-prototype');
			this.groupName = this.$field.data('name');
			this.groupIndex = this.$field.data('index');
			this.isBuilder = (this.$field.parents('.us-bld-window').length) ? true : false;
			this.isSortable = this.$field.hasClass('sortable');
			this.isAccordion = this.$field.hasClass('type_accordion');
			this.groupParams = [];
			this.groupTranslations = (this.$field.find('.usof-form-group-translations')[0].onclick() || {});
			this.ajaxData = {
				group: this.groupName,
				index: this.groupIndex
			};

			if (this.isBuilder) {
				this.$parentElementForm = this.$field.closest('.usof-form');
				this.elementName = this.$parentElementForm.usMod('for');
				this.$builderWindow = this.$field.closest('.us-bld-window');

				this.ajaxData.action = 'ushb_add_group_params';
				this.ajaxData.element = this.elementName;
			} else {
				this.$parentSection = this.$field.closest('.usof-section');
				this.sectionName = this.$parentSection.data('id');
				this.ajaxData.action = 'usof_add_group_params';
				this.ajaxData.section = this.sectionName;

				this.$field.find('.usof-form-group-item').each(function(i, groupParams){
					var $groupParams = $(groupParams);
					if ( ! $groupParams.parent().hasClass('usof-form-group-prototype')) {
						this.groupParams.push(new $usof.GroupParams($groupParams));
					}
				}.bind(this));

			}

			this.$btnAddGroup.on('click', function(){
				this.groupAdd();
			}.bind(this));

			this.$btnDelGroup.live('click', function(event){ // TODO: check whether adding event for each button is leaner
				event.stopPropagation();
				var $btn = $(event.target),
					$group = $btn.closest('.usof-form-group-item');
				this.groupDel($group);
			}.bind(this));

			if (this.isAccordion) {

				this.$sections = this.$field.find('.usof-form-group-item');
				this.$sectionTitles = this.$field.find('.usof-form-group-item-title');
				this.$sectionTitles.live('click', function(event){
					this.$sections = this.$field.find('.usof-form-group-item');
					var $parentSection = $(event.target).parent();
					if ($parentSection.hasClass('active')) {
						$parentSection.removeClass('active').children('.usof-form-group-item-content').slideUp();
					} else {
						var $previousActive = this.$sections.filter('.active');
						$previousActive.removeClass('active').children('.usof-form-group-item-content').slideUp();
						$parentSection.addClass('active').children('.usof-form-group-item-content').slideDown();
					}

				}.bind(this));
			}

			if (this.isSortable) {
				this.$field.on('dragstart', function(event){
					event.preventDefault();
				});
				this.$body = $(document.body);
				this.$window = $(window);

				this.$dragControls = this.$field.find('.usof-control-move');

				this.$dragshadow = $('<div class="us-bld-editor-dragshadow"></div>');

				this.$dragControls.live('mousedown', this._dragStart.bind(this));

				this._events = {
					_maybeDragMove: this._maybeDragMove.bind(this),
					_dragMove: this._dragMove.bind(this),
					_dragEnd: this._dragEnd.bind(this)
				};
			}
		},
		_reInitParams: function(){
			this.groupParams = [];
			this.$field.find('.usof-form-group-item').each(function(i, groupParams){
				var $groupParams = $(groupParams);
				if ( ! $groupParams.parent().hasClass('usof-form-group-prototype')) {
					this.groupParams.push(new $usof.GroupParams($groupParams));
				}
			}.bind(this));
			if ( ! this.isBuilder) {
				if ($.isEmptyObject($usof.instance.valuesChanged)) {
					clearTimeout($usof.instance.saveStateTimer);
					$usof.instance.$saveControl.usMod('status', 'notsaved');
				}
				$usof.instance.valuesChanged[this.groupName] = this.getValue();
			}
		},
		setValue: function(value){
			this.groupParams = [];
			this.$field.find('.usof-form-group-item').each(function(i, groupParams){
				var $groupParams = $(groupParams);
				if ( ! $groupParams.parent().hasClass('usof-form-group-prototype')) {
					$groupParams.remove();
				}
			}.bind(this));
			$.each(value, function(index, paramsValues){
				this.$btnAddGroup.before(this.$groupPrototype.html());
				var $groupParams = this.$field.find('.usof-form-group-item').last();
				var groupParams = new $usof.GroupParams($groupParams);
				groupParams.setValues(paramsValues, 1);
				for (var fieldId in groupParams.fields) {
					if (!groupParams.fields.hasOwnProperty(fieldId)) continue;
					groupParams.fields[fieldId].trigger('change');
					break;
				}
			}.bind(this));
			this._reInitParams();
		},
		getValue: function(){
			var result = [];
			$.each(this.groupParams, function (i, groupParams){
				result.push(groupParams.getValues());
			});
			return result;
		},
		groupAdd: function(){
			this.$btnAddGroup.addClass('adding');
			this.$btnAddGroup.before(this.$groupPrototype.html());
			var $groupParams = this.$field.find('.usof-form-group-item').last();
			this.groupParams.push(new $usof.GroupParams($groupParams));
			if ( ! this.isBuilder) {
				if ($.isEmptyObject($usof.instance.valuesChanged)) {
					clearTimeout($usof.instance.saveStateTimer);
					$usof.instance.$saveControl.usMod('status', 'notsaved');
				}
				$usof.instance.valuesChanged[this.groupName] = this.getValue();
			}
			this.$btnAddGroup.removeClass('adding');
		},
		groupDel: function($group){
			if (!confirm(this.groupTranslations.deleteConfirm)) return false;
			$group.addClass('deleting');
			$group.remove();
			this._reInitParams();
		},
		// Drag'n'drop functions
		_dragStart: function(event){
			event.stopPropagation();
			this.$draggedElm = $(event.target).closest('.usof-form-group-item');
			this.detached = false;
			this._updateBlindSpot(event);
			this.elmPointerOffset = [parseInt(event.pageX), parseInt(event.pageY)];
			this.$body.on('mousemove', this._events._maybeDragMove);
			this.$window.on('mouseup', this._events._dragEnd);
		},
		_updateBlindSpot: function(event){
			this.blindSpot = [event.pageX, event.pageY];
		},
		_isInBlindSpot: function(event){
			return Math.abs(event.pageX - this.blindSpot[0]) <= 20 && Math.abs(event.pageY - this.blindSpot[1]) <= 20;
		},
		_maybeDragMove: function(event){
			event.stopPropagation();
			if (this._isInBlindSpot(event)) return;
			this.$body.off('mousemove', this._events._maybeDragMove);
			this._detach();
			this.$body.on('mousemove', this._events._dragMove);
		},
		_detach: function(event){
			var offset = this.$draggedElm.offset();
			this.elmPointerOffset[0] -= offset.left;
			this.elmPointerOffset[1] -= offset.top;
			this.$draggedElm.find('.usof-form-group-item-title').hide();
			if ( ! this.isAccordion || this.$draggedElm.hasClass('active')) {
				this.$draggedElm.find('.usof-form-group-item-content').hide();
			}
			this.$dragshadow.css({
				width: this.$draggedElm.outerWidth()
			}).insertBefore(this.$draggedElm);

			this.$draggedElm.addClass('dragged').css({
				position: 'absolute',
				'pointer-events': 'none',
				zIndex: 10000,
				width: this.$draggedElm.width(),
				height: this.$draggedElm.height()
			}).css(offset).appendTo(this.$body);
			if (this.isBuilder) {
				this.$builderWindow.addClass('dragged');
			}
			this.detached = true;
		},
		_dragMove: function(event){
			event.stopPropagation();
			this.$draggedElm.css({
				left: event.pageX - this.elmPointerOffset[0],
				top: event.pageY - this.elmPointerOffset[1]
			});
			if (this._isInBlindSpot(event)) return;
			var elm = event.target;
			// Checking two levels up
			for (var level = 0; level <= 2; level++, elm = elm.parentNode) {
				if (this._isShadow(elm)) return;
				// Workaround for IE9-10 that don't support css pointer-events property
				if (this._hasClass(elm, 'detached')) {
					this.$draggedElm.detach();
					break;
				}
				if (this._isSortable(elm)) {

					// Dropping element before or after sortables based on their relative position in DOM
					var nextElm = elm.previousSibling,
						shadowAtLeft = false;
					while (nextElm) {
						if (nextElm == this.$dragshadow[0]) {
							shadowAtLeft = true;
							break;
						}
						nextElm = nextElm.previousSibling;
					}
					this.$dragshadow[shadowAtLeft ? 'insertAfter' : 'insertBefore'](elm);
					this._dragDrop(event);
					break;
				}
			}
		},
		/**
		 * Complete drop
		 * @param event
		 */
		_dragDrop: function(event){
			this._updateBlindSpot(event);
		},
		_dragEnd: function(event){
			this.$body.off('mousemove', this._events._maybeDragMove).off('mousemove', this._events._dragMove);
			this.$window.off('mouseup', this._events._dragEnd);
			if (this.detached) {
				this.$draggedElm.removeClass('dragged').removeAttr('style').insertBefore(this.$dragshadow);
				this.$dragshadow.detach();
				if (this.isBuilder) {
					this.$builderWindow.removeClass('dragged');
				}
				this.$draggedElm.find('.usof-form-group-item-title').show();
				if ( ! this.isAccordion || this.$draggedElm.hasClass('active')) {
					this.$draggedElm.find('.usof-form-group-item-content').show();
				}
				this._reInitParams();
			}
		},
		_hasClass: function(elm, cls){
			return (' ' + elm.className + ' ').indexOf(' ' + cls + ' ') > -1;
		},
		_isShadow: function(elm){
			return this._hasClass(elm, 'usof-form-group-dragshadow');
		},
		_isSortable: function(elm){
			return this._hasClass(elm, 'usof-form-group-item');
		}
	};


	$.fn.usofGroup = function(options){
		return new $usof.Group(this, options);
	};

}(jQuery);


/**
 * USOF Core
 */
!function($){

	$usof.ajaxUrl = $('.usof-container').data('ajaxurl');

	// Prototype mixin for all classes working with fields
	if ($usof.mixins === undefined) $usof.mixins = {};
	$usof.mixins.Fieldset = {
		/**
		 * Initialize fields inside of a container
		 * @param {jQuery} $container
		 */
		initFields: function($container){
			if (this.$fields === undefined) this.$fields = {};
			if (this.fields === undefined) this.fields = {};
			if (this.groups === undefined) this.groups = {};
			// Showing conditions (fieldId => condition)
			if (this.showIf === undefined) this.showIf = {};
			// Showing dependencies (fieldId => affected field ids)
			if (this.showIfDeps === undefined) this.showIfDeps = {};
			var groupElms = [];
			$.each($container.find('.usof-form-row, .usof-form-wrapper, .usof-form-group'), function(index, elm){
				var $field = $(elm),
					name = $field.data('name'),
					isRow = $field.hasClass('usof-form-row'),
					isGroup = $field.hasClass('usof-form-group'),
					isInGroup = $field.parents('.usof-form-group').length,
					$showIf = $field.find((isRow || isGroup)? '> .usof-form-row-showif' : '> .usof-form-wrapper-content > .usof-form-wrapper-showif');
				this.$fields[name] = $field;
				if ($showIf.length > 0) {
					this.showIf[name] = $showIf[0].onclick() || [];
					// Writing dependencies
					var showIfVars = this.getShowIfVariables(this.showIf[name]);
					for (var i = 0; i < showIfVars.length; i++) {
						if (this.showIfDeps[showIfVars[i]] === undefined) this.showIfDeps[showIfVars[i]] = [];
						this.showIfDeps[showIfVars[i]].push(name);
					}
				}
				if (isRow && ( ! isInGroup || this.isGroupParams)) {
					this.fields[name] = $field.usofField(elm);
				} else if (isGroup) {
					this.groups[name] = $field.usofGroup(elm);
				}
			}.bind(this));
			for (var fieldName in this.showIfDeps) {
				if (!this.showIfDeps.hasOwnProperty(fieldName) || this.fields[fieldName] === undefined) continue;
				this.fields[fieldName].on('change', function(field){
					this.updateVisibility(field.name);
				}.bind(this));
			}
		},
		/**
		 * Show / hide the field based on its showIf condition
		 */
		updateVisibility: function(fieldName, animate){
			animate = typeof animate !== 'undefined' ? animate : 1;
			var anmationDuration = (animate) ? 400 : 0;
			$.each(this.showIfDeps[fieldName], function(index, depFieldId){
				// Getting stored value to take animations into account as well
				var isShown = this.$fields[depFieldId].data('isShown'),
					shouldBeShown = this.executeShowIf(this.showIf[depFieldId], this.getValue.bind(this));
				if (isShown === undefined) {
					isShown = (this.$fields[depFieldId].css('display') != 'none');
				}
				if (shouldBeShown && !isShown) {
					this.fireFieldEvent(this.$fields[depFieldId], 'beforeShow');
					this.$fields[depFieldId].stop(true, false).slideDown(anmationDuration, function(){
						this.fireFieldEvent(this.$fields[depFieldId], 'afterShow');
					}.bind(this));
					this.$fields[depFieldId].data('isShown', true);
				} else if (!shouldBeShown && isShown) {
					this.fireFieldEvent(this.$fields[depFieldId], 'beforeHide');
					this.$fields[depFieldId].stop(true, false).slideUp(anmationDuration, function(){
						this.fireFieldEvent(this.$fields[depFieldId], 'afterHide');
					}.bind(this));
					this.$fields[depFieldId].data('isShown', false);
				}
			}.bind(this));
		},
		/**
		 * Get all field names that affect the given 'show_if' condition
		 * @param {Array} condition
		 * @returns {Array}
		 */
		getShowIfVariables: function(condition){
			if (!$.isArray(condition) || condition.length < 3) {
				return [];
			} else if ($.inArray(condition[1].toLowerCase(), ['and', 'or']) != -1) {
				// Complex or / and statement
				var vars = this.getShowIfVariables(condition[0]),
					index = 2;
				while (condition[index] !== undefined) {
					vars = vars.concat(this.getShowIfVariables(condition[index]));
					index = index + 2;
				}
				return vars;
			} else {
				return [condition[0]];
			}
		},
		/**
		 * Execute 'show_if' condition
		 * @param {Array} condition
		 * @param {Function} getValue Function to get the needed value
		 * @returns {Boolean} Should be shown?
		 */
		executeShowIf: function(condition, getValue){
			var result = true;
			if (!$.isArray(condition) || condition.length < 3) {
				return result;
			} else if ($.inArray(condition[1].toLowerCase(), ['and', 'or']) != -1) {
				// Complex or / and statement
				result = this.executeShowIf(condition[0], getValue);
				var index = 2;
				while (condition[index] !== undefined) {
					condition[index - 1] = condition[index - 1].toLowerCase();
					if (condition[index - 1] == 'and') {
						result = (result && this.executeShowIf(condition[index], getValue));
					} else if (condition[index - 1] == 'or') {
						result = (result || this.executeShowIf(condition[index], getValue));
					}
					index = index + 2;
				}
			} else {
				var value = getValue(condition[0]);
				if (value === undefined) return true;
				if (condition[1] == '=') {
					result = ( value == condition[2] );
				} else if (condition[1] == '!=' || condition[1] == '<>') {
					result = ( value != condition[2] );
				} else if (condition[1] == 'in') {
					result = ( !$.isArray(condition[2]) || $.inArray(value, condition[2]) != -1 );
				} else if (condition[1] == 'not in') {
					result = ( !$.isArray(condition[2]) || $.inArray(value, condition[2]) == -1 );
				} else if (condition[1] == 'has') {
					result = ( !$.isArray(value) || $.inArray(condition[2], value) != -1 );
				} else if (condition[1] == '<=') {
					result = ( value <= condition[2] );
				} else if (condition[1] == '<') {
					result = ( value < condition[2] );
				} else if (condition[1] == '>') {
					result = ( value > condition[2] );
				} else if (condition[1] == '>=') {
					result = ( value >= condition[2] );
				} else {
					result = true;
				}
			}
			return result;
		},
		/**
		 * Find all the fields within $container and fire a certain event there
		 * @param $container jQuery
		 * @param trigger string
		 */
		fireFieldEvent: function($container, trigger){
			var isRow = $container.hasClass('usof-form-row'),
				hideShowEvent = (trigger == 'beforeShow' || trigger == 'afterShow' || trigger == 'beforeHide' || trigger == 'afterHide');
			if (!isRow) {
				$container.find('.usof-form-row').each(function(index, block){
					var $block = $(block),
						isShown = $block.data('isShown');
					if (isShown === undefined) {
						isShown = ($block.css('display') != 'none');
					}
					// The block is not actually shown or hidden in this case
					if (hideShowEvent && !isShown) return;
					if ($block.data('usofField') == undefined) return;
					$block.data('usofField').trigger(trigger);
				}.bind(this));
			} else {
				$container.data('usofField').trigger(trigger);
			}
		},

		getValue: function(id){
			if (this.fields[id] === undefined) return undefined;
			return this.fields[id].getValue();
		},

		/**
		 * Set some particular field value
		 * @param {String} id
		 * @param {String} value
		 * @param {Boolean} quiet Don't fire onchange events
		 */
		setValue: function(id, value, quiet){
			if (this.fields[id] === undefined) return;
			var shouldFireShow = !this.fields[id].inited;
			if (shouldFireShow) {
				this.fields[id].trigger('beforeShow');
				this.fields[id].trigger('afterShow');
			}
			this.fields[id].setValue(value, quiet);
			if (shouldFireShow) {
				this.fields[id].trigger('beforeHide');
				this.fields[id].trigger('afterHide');
			}
		},

		getValues: function(id){
			var values = {};
			// Regular values
			for (var fieldId in this.fields) {
				if (!this.fields.hasOwnProperty(fieldId)) continue;
				values[fieldId] = this.getValue(fieldId);
			}
			// Groups
			for (var groupId in this.groups) {
				values[groupId] = this.groups[groupId].getValue();
			}
			return values;
		},

		/**
		 * Set the values
		 * @param {Object} values
		 * @param {Boolean} quiet Don't fire onchange events, just change the interface
		 */
		setValues: function(values, quiet){
			// Regular values
			for (var fieldId in values) {
				if (!values.hasOwnProperty(fieldId) || this.fields[fieldId] == undefined) continue;
				this.setValue(fieldId, values[fieldId], quiet);
				if (!quiet) {
					this.fields[fieldId].trigger('change', [values[fieldId]]);
				}
			}
			// Groups
			for (var groupId in this.groups) {
				this.groups[groupId].setValue(values[groupId]);
			}
			if (quiet) {
				// Update fields visibility anyway
				for (var fieldName in this.showIfDeps) {
					if (!this.showIfDeps.hasOwnProperty(fieldName) || this.fields[fieldName] === undefined) continue;
					this.updateVisibility(fieldName, 0);
				}
			}
		},
		/**
		 * JavaScript representation of us_prepare_icon_tag helper function + removal of wrong symbols
		 * @param {String} iconClass
		 * @returns {String}
		 */
		prepareIconTag: function(iconValue){
			iconValue = iconValue.trim().split('|');
			if (iconValue.length != 2) {
				return '';
			}
			var iconTag = '';
			iconValue[0] = iconValue[0].toLowerCase();
			if (iconValue[0] == 'material') {
				iconTag = '<i class="material-icons">' + iconValue[1] + '</i>';
			} else {
				if (iconValue[1].substr(0, 3) == 'fa-') {
					iconTag = '<i class="' + iconValue[0] + ' ' + iconValue[1] + '"></i>';
				} else {
					iconTag = '<i class="' + iconValue[0] + ' fa-' + iconValue[1] + '"></i>';
				}
			}

			return iconTag
		}
	};

	$usof.GroupParams = function(container){
		this.$container = $(container);
		this.$group = this.$container.closest('.usof-form-group');
		this.group = this.$group.data('name');
		this.isGroupParams = true;
		this.isBuilder = (this.$container.parents('.us-bld-window').length) ? true : false;

		this.initFields(this.$container);
		this.fireFieldEvent(this.$container, 'beforeShow');
		this.fireFieldEvent(this.$container, 'afterShow');

		for (var fieldName in this.showIfDeps) {
			this.updateVisibility(fieldName, 0);
		}

		this.paramsTitle = (this.$group.data('params_title') != undefined) ? decodeURIComponent(this.$group.data('params_title')) : '';
		if (this.paramsTitle != undefined && this.paramsTitle != '') {
			this.$title = this.$container.find('.usof-form-group-item-title');
			for (var fieldId in this.fields) {
				if (!this.fields.hasOwnProperty(fieldId)) continue;
				this.fields[fieldId].on('change', function(){
					var paramsTitleResult = this.paramsTitle;
					for (var fieldId in this.fields) {
						if (!this.fields.hasOwnProperty(fieldId)) continue;
						if (paramsTitleResult.indexOf('{{'+fieldId+'}}') !== -1){
							paramsTitleResult = paramsTitleResult.replace('{{'+fieldId+'}}', this.getValue(fieldId));
						}
					}
					this.$title.html(paramsTitleResult);
				}.bind(this));
			}
		}

		if ( ! this.isBuilder) {
			for (var fieldId in this.fields) {
				if (!this.fields.hasOwnProperty(fieldId)) continue;
				this.fields[fieldId].on('change', function(field, value){
					if ($.isEmptyObject($usof.instance.valuesChanged)) {
						clearTimeout($usof.instance.saveStateTimer);
						$usof.instance.$saveControl.usMod('status', 'notsaved');
					}
					$usof.instance.valuesChanged[this.group] = $usof.instance.groups[this.group].getValue();
				}.bind(this));
			}
		}

	};

	$.extend($usof.GroupParams.prototype, $usof.mixins.Fieldset);

	var USOF_Meta = function(container){
		this.$container = $(container);
		this.initFields(this.$container);

		this.fireFieldEvent(this.$container, 'beforeShow');
		this.fireFieldEvent(this.$container, 'afterShow');

		for (var fieldId in this.fields) {
			if (!this.fields.hasOwnProperty(fieldId)) continue;
			this.fields[fieldId].on('change', function(field, value){
				USMMSettings = {};
				for (var savingFieldId in this.fields) {
					USMMSettings[savingFieldId] = this.fields[savingFieldId].getValue();
				}
				$(document.body).trigger('usof_mm_save');
			}.bind(this));
		}

	};
	$.extend(USOF_Meta.prototype, $usof.mixins.Fieldset, {});

	var USOF = function(container){
		if (window.$usof === undefined) window.$usof = {};
		$usof.instance = this;
		this.$container = $(container);
		this.$title = this.$container.find('.usof-header-title h2');

		this.initFields(this.$container);

		this.active = null;
		this.$sections = {};
		this.$sectionContents = {};
		this.sectionFields = {};
		$.each(this.$container.find('.usof-section'), function(index, section){
			var $section = $(section),
				sectionId = $section.data('id');
			this.$sections[sectionId] = $section;
			this.$sectionContents[sectionId] = $section.find('.usof-section-content');
			if ($section.hasClass('current')) {
				this.active = sectionId;
			}
			this.sectionFields[sectionId] = [];
			$.each($section.find('.usof-form-row'), function(index, row){
				var $row = $(row),
					fieldName = $row.data('name');
				if (fieldName) {
					this.sectionFields[sectionId].push(fieldName);
				}
			}.bind(this));
		}.bind(this));

		this.sectionTitles = {};
		$.each(this.$container.find('.usof-nav-item.level_1'), function(index, item){
			var $item = $(item),
				sectionId = $item.data('id');
			this.sectionTitles[sectionId] = $item.find('.usof-nav-title').html();
		}.bind(this));

		this.navItems = this.$container.find('.usof-nav-item.level_1, .usof-section-header');
		this.sectionHeaders = this.$container.find('.usof-section-header');
		this.sectionHeaders.each(function(index, item){
			var $item = $(item),
				sectionId = $item.data('id');
			$item.on('click', function(){
				this.openSection(sectionId);
			}.bind(this));
		}.bind(this));

		// Handling initial document hash
		if (document.location.hash && document.location.hash.indexOf('#!') == -1) {
			this.openSection(document.location.hash.substring(1));
		}

		// Initializing fields at the shown section
		if (this.$sections[this.active] !== undefined) {
			this.fireFieldEvent(this.$sections[this.active], 'beforeShow');
			this.fireFieldEvent(this.$sections[this.active], 'afterShow');
		}

		// Save action
		this.$saveControl = this.$container.find('.usof-control.for_save');
		this.$saveBtn = this.$saveControl.find('.usof-button').on('click', this.save.bind(this));
		this.$saveMessage = this.$saveControl.find('.usof-control-message');
		this.valuesChanged = {};
		this.saveStateTimer = null;
		for (var fieldId in this.fields) {
			if (!this.fields.hasOwnProperty(fieldId)) continue;
			this.fields[fieldId].on('change', function(field, value){
				if ($.isEmptyObject(this.valuesChanged)) {
					clearTimeout(this.saveStateTimer);
					this.$saveControl.usMod('status', 'notsaved');
				}
				this.valuesChanged[field.name] = value;
			}.bind(this));
		}

		this.$window = $(window);
		this.$header = this.$container.find('.usof-header');

		this._events = {
			scroll: this.scroll.bind(this),
			resize: this.resize.bind(this)
		};

		this.resize();
		this.$window.on('resize load', this._events.resize);
		this.$window.on('scroll', this._events.scroll);
		this.$window.on('hashchange',function(){
			this.openSection(document.location.hash.substring(1));
		}.bind(this));

		$(window).bind('keydown', function(event) {
			if (event.ctrlKey || event.metaKey) {
				if (String.fromCharCode(event.which).toLowerCase() == 's') {
					event.preventDefault();
					$usof.instance.save();
				}
			}
		});
	};
	$.extend(USOF.prototype, $usof.mixins.Fieldset, {
		scroll: function(){
			this.$container.toggleClass('footer_fixed', this.$window.scrollTop() > this.headerAreaSize);
		},

		resize: function(){
			if ( ! this.$header.length) return;
			this.headerAreaSize = this.$header.offset().top + this.$header.outerHeight();
			this.scroll();
		},

		openSection: function(sectionId){
			if (sectionId == this.active || this.$sections[sectionId] === undefined) return;
			if (this.$sections[this.active] !== undefined) {
				this.hideSection();
			}
			this.showSection(sectionId);
		},

		showSection: function(sectionId){
			var curItem = this.navItems.filter('[data-id="' + sectionId + '"]');
			curItem.addClass('current');
			this.fireFieldEvent(this.$sectionContents[sectionId], 'beforeShow');
			this.$sectionContents[sectionId].stop(true, false).fadeIn();
			this.$title.html(this.sectionTitles[sectionId]);
			this.fireFieldEvent(this.$sectionContents[sectionId], 'afterShow');
			// Item popup
			var itemPopup = curItem.find('.usof-nav-popup');
			if (itemPopup.length > 0) {
				// Current usof_visited_new_sections cookie
				var matches = document.cookie.match(/(?:^|; )usof_visited_new_sections=([^;]*)/),
					cookieValue = matches ? decodeURIComponent(matches[1]) : '',
					visitedNewSections = (cookieValue == '') ? [] : cookieValue.split(',');
				if (visitedNewSections.indexOf(sectionId) == -1) {
					visitedNewSections.push(sectionId);
					document.cookie = 'usof_visited_new_sections=' + visitedNewSections.join(',')
				}
				itemPopup.remove();
			}
			this.active = sectionId;
		},

		hideSection: function(){
			this.navItems.filter('[data-id="' + this.active + '"]').removeClass('current');
			this.fireFieldEvent(this.$sectionContents[this.active], 'beforeHide');
			this.$sectionContents[this.active].stop(true, false).hide();
			this.$title.html('');
			this.fireFieldEvent(this.$sectionContents[this.active], 'afterHide');
			this.active = null;
		},

		/**
		 * Save the new values
		 */
		save: function(){
			if ($.isEmptyObject(this.valuesChanged)) return;
			clearTimeout(this.saveStateTimer);
			this.$saveMessage.html('');
			this.$saveControl.usMod('status', 'loading');

			$.ajax({
				type: 'POST',
				url: $usof.ajaxUrl,
				dataType: 'json',
				data: {
					action: 'usof_save',
					usof_options: JSON.stringify(this.valuesChanged),
					_wpnonce: this.$container.find('[name="_wpnonce"]').val(),
					_wp_http_referer: this.$container.find('[name="_wp_http_referer"]').val()
				},
				success: function(result){
					if (result.success) {
						this.valuesChanged = {};
						this.$saveMessage.html(result.data.message);
						this.$saveControl.usMod('status', 'success');
						this.saveStateTimer = setTimeout(function(){
							this.$saveMessage.html('');
							this.$saveControl.usMod('status', 'clear');
						}.bind(this), 4000);
					} else {
						this.$saveMessage.html(result.data.message);
						this.$saveControl.usMod('status', 'error');
						this.saveStateTimer = setTimeout(function(){
							this.$saveMessage.html('');
							this.$saveControl.usMod('status', 'notsaved');
						}.bind(this), 4000);
					}
				}.bind(this)
			});
		}
	});

	$(function(){
		new USOF('.usof-container');

		$.each($('.usof-metabox'), function(index, item){
			new USOF_Meta(item);
		});

		$(document.body).off('usof_mm_load').on('usof_mm_load', function(){
			$.each($('.us-mm-settings'), function(index, item){
				new USOF_Meta(item);
			});
		});
	});
}(jQuery);
