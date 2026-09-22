(function ($) {
	'use strict';

	if (typeof pgfw_pdf_builder_param === 'undefined') {
		return;
	}

	var param = pgfw_pdf_builder_param;
	var data = param.data || { post_types: {}, layouts: {}, meta_fields: {}, templates: [], page_size: { width: 794, height: 1123 } };
	var pageSize = data.page_size || { width: 794, height: 1123 };
	var templates = data.templates || [];

	// Working copy of every post type's pages/blocks/background/watermark, keyed
	// by post type. Starts from whatever was saved, and edits only persist when
	// "Save Layout" is clicked.
	var layoutsByType = {};
	Object.keys(data.post_types || {}).forEach(function (postType) {
		var saved = (data.layouts && data.layouts[postType]) || {};
		var savedPages = saved.pages;
		layoutsByType[postType] = {
			pages: (Array.isArray(savedPages) && savedPages.length ? savedPages : [{ blocks: [] }]).map(function (page) {
				return { blocks: Array.isArray(page.blocks) ? page.blocks.slice() : [] };
			}),
			background_color: saved.background_color || '',
			watermark: $.extend({ enable: false, text: '', color: '#999999', opacity: 0.2, font_size: 60 }, saved.watermark || {})
		};
	});

	var currentPostType = Object.keys(data.post_types || {})[0] || '';
	var currentPageIndex = 0;
	var selectedBlockId = null;
	var blockCounter = 0;
	var SNAP = 10;

	function currentLayout() {
		if (!layoutsByType[currentPostType]) {
			layoutsByType[currentPostType] = { pages: [{ blocks: [] }], background_color: '', watermark: { enable: false, text: '', color: '#999999', opacity: 0.2, font_size: 60 } };
		}
		return layoutsByType[currentPostType];
	}

	function currentPages() {
		var layout = currentLayout();
		if (!layout.pages.length) {
			layout.pages = [{ blocks: [] }];
		}
		return layout.pages;
	}

	function currentBlocks() {
		var pages = currentPages();
		if (currentPageIndex >= pages.length) {
			currentPageIndex = pages.length - 1;
		}
		return pages[currentPageIndex].blocks;
	}

	function findBlock(id) {
		var blocks = currentBlocks();
		for (var i = 0; i < blocks.length; i++) {
			if (blocks[i].id === id) {
				return blocks[i];
			}
		}
		return null;
	}

	function snapVal(v) {
		if (!$('#pgfw-pdf-builder-snap').is(':checked')) {
			return v;
		}
		return Math.round(v / SNAP) * SNAP;
	}

	function newBlockDefaults(type) {
		blockCounter++;
		return {
			id: 'pgfw_block_' + Date.now() + '_' + blockCounter,
			type: type,
			x: 40,
			y: 40,
			width: type === 'image' ? 150 : (type === 'rectangle' ? 200 : 220),
			height: type === 'image' ? 150 : (type === 'rectangle' ? 4 : 30),
			font_size: 14,
			color: '#000000',
			align: 'left',
			bold: false,
			italic: false,
			background_color: type === 'rectangle' ? '#dddddd' : '',
			border_width: 0,
			border_color: '#000000',
			source: type === 'image' ? 'featured_image' : (type === 'text' ? 'static' : ''),
			content: type === 'text' ? 'Text' : '',
			meta_key: '',
			label: ''
		};
	}

	function escapeHtml(str) {
		return $('<div>').text(str == null ? '' : str).html();
	}

	// A block's *actual* text color often only makes sense against the
	// background it was designed for (e.g. white text meant to sit on a
	// colored header rectangle). Once a block is moved, resized, or simply
	// happens to land over a different part of the canvas while editing, that
	// same color can become invisible (white-on-white) - the block's binding
	// is still correct, it just can't be *seen* in the editor. This badge is
	// always rendered with its own fixed, high-contrast style (independent of
	// the block's color/background) so every block's binding stays visibly
	// identifiable no matter where it sits or what colors it uses. It has no
	// effect on the actual generated PDF - that still uses the block's real
	// styling, exactly as configured.
	function blockBadgeHtml(block) {
		var label = '';
		if ('text' === block.type) {
			var sourceLabels = {
				static: 'Static Text',
				post_title: 'Post Title',
				post_date: 'Post Date',
				post_author: 'Post Author',
				post_excerpt: 'Post Excerpt',
				post_content: 'Post Content'
			};
			label = sourceLabels[block.source] || 'Text';
		} else if ('meta' === block.type) {
			label = 'Meta: ' + (block.meta_key || '(none selected)');
		} else if ('image' === block.type) {
			label = 'featured_image' === block.source ? 'Featured Image' : 'Image';
		} else {
			return '';
		}
		return '<span class="pgfw-pdf-block__badge">' + escapeHtml(label) + '</span>';
	}

	function blockPreviewHtml(block) {
		var badge = blockBadgeHtml(block);

		if (block.type === 'rectangle') {
			return '';
		}
		if (block.type === 'image') {
			if (block.source === 'featured_image') {
				return badge + '<div class="pgfw-pdf-block__placeholder">Featured Image</div>';
			}
			if (block.content) {
				return badge + '<img src="' + block.content + '" alt="" style="width:100%;height:100%;object-fit:cover;" />';
			}
			return badge + '<div class="pgfw-pdf-block__placeholder">' + (param.i18n.upload_image || 'Choose Image') + '</div>';
		}
		if (block.type === 'meta') {
			var label = block.label ? block.label : '';
			var key = block.meta_key ? block.meta_key : '(select a meta key)';
			return badge + '<div>' + escapeHtml(label) + '<em>{' + escapeHtml(key) + '}</em></div>';
		}
		var sourceLabels = {
			static: block.content || 'Text',
			post_title: '{Post Title}',
			post_date: '{Post Date}',
			post_author: '{Post Author}',
			post_excerpt: '{Post Excerpt}',
			post_content: '{Post Content}'
		};
		return badge + '<div>' + escapeHtml(sourceLabels[block.source] || block.content || 'Text') + '</div>';
	}

	function blockCssVars(block) {
		var css = {
			left: block.x + 'px',
			top: block.y + 'px',
			width: block.width + 'px',
			height: block.height + 'px'
		};
		if (block.type !== 'rectangle' && block.type !== 'image') {
			css.fontSize = block.font_size + 'px';
			css.color = block.color;
			css.textAlign = block.align;
			css.fontWeight = block.bold ? 'bold' : 'normal';
			css.fontStyle = block.italic ? 'italic' : 'normal';
		}
		css.backgroundColor = block.background_color || '';
		css.border = block.border_width ? (block.border_width + 'px solid ' + (block.border_color || '#000')) : '';
		return css;
	}

	function renderPageTabs() {
		var $tabs = $('#pgfw-pdf-builder-page-tabs');
		$tabs.empty();
		currentPages().forEach(function (page, idx) {
			var $tab = $('<button type="button" class="pgfw-pdf-builder-page-tab' + (idx === currentPageIndex ? ' is-active' : '') + '">' + (idx + 1) + '</button>');
			$tab.on('click', function () {
				currentPageIndex = idx;
				selectedBlockId = null;
				renderPageTabs();
				renderCanvas();
				renderProperties();
			});
			$tabs.append($tab);
		});
	}

	function renderCanvas() {
		var $canvas = $('#pgfw-pdf-builder-canvas');
		var layout = currentLayout();
		$canvas.css({
			width: pageSize.width + 'px',
			height: pageSize.height + 'px',
			backgroundColor: layout.background_color || '#ffffff'
		});
		$canvas.empty();

		if (layout.watermark && layout.watermark.enable && layout.watermark.text) {
			$canvas.append(
				$('<div class="pgfw-pdf-builder-watermark"></div>')
					.text(layout.watermark.text)
					.css({
						color: layout.watermark.color || '#999999',
						opacity: layout.watermark.opacity != null ? layout.watermark.opacity : 0.2,
						fontSize: (layout.watermark.font_size || 60) + 'px'
					})
			);
		}

		currentBlocks().forEach(function (block) {
			var $el = $('<div class="pgfw-pdf-block pgfw-pdf-block--' + block.type + '" data-id="' + block.id + '"></div>')
				.css(blockCssVars(block))
				.append($('<div class="pgfw-pdf-block__inner"></div>').html(blockPreviewHtml(block)));

			var $remove = $('<span class="pgfw-pdf-block__remove" title="Remove">&times;</span>');
			$remove.on('click', function (e) {
				e.stopPropagation();
				removeBlock(block.id);
			});
			$el.append($remove);

			if (block.id === selectedBlockId) {
				$el.addClass('is-selected');
			}

			$el.on('mousedown click', function () {
				selectBlock(block.id);
			});

			$el.draggable({
				containment: 'parent',
				stop: function (event, ui) {
					block.x = snapVal(ui.position.left);
					block.y = snapVal(ui.position.top);
					renderCanvas();
				}
			});
			$el.resizable({
				containment: 'parent',
				minWidth: 4,
				minHeight: 4,
				stop: function (event, ui) {
					block.width = snapVal(ui.size.width);
					block.height = snapVal(ui.size.height);
					renderCanvas();
				}
			});

			$canvas.append($el);
		});
	}

	function removeBlock(id) {
		if (!window.confirm(param.i18n.confirm_delete || 'Remove this block?')) {
			return;
		}
		var blocks = currentBlocks();
		var idx = -1;
		for (var i = 0; i < blocks.length; i++) {
			if (blocks[i].id === id) {
				idx = i;
				break;
			}
		}
		if (idx > -1) {
			blocks.splice(idx, 1);
		}
		if (selectedBlockId === id) {
			selectedBlockId = null;
		}
		renderCanvas();
		renderProperties();
	}

	function selectBlock(id) {
		selectedBlockId = id;
		renderCanvas();
		renderProperties();
		$('#pgfw-pdf-builder-block-actions').toggle(!!id);
	}

	function metaFieldOptions(postType) {
		return (data.meta_fields && data.meta_fields[postType]) || [];
	}

	function field(type, name, label, value, options) {
		var out = '<div class="pgfw-pdf-builder-field"><label>' + escapeHtml(label) + '</label><select data-field="' + name + '">';
		options.forEach(function (opt) {
			out += '<option value="' + escapeHtml(opt[0]) + '"' + (opt[0] === value ? ' selected' : '') + '>' + escapeHtml(opt[1]) + '</option>';
		});
		out += '</select></div>';
		return out;
	}

	function checkboxRow(name, label, value) {
		return '<div class="pgfw-pdf-builder-field"><label><input type="checkbox" data-field="' + name + '"' + (value ? ' checked' : '') + ' /> ' + escapeHtml(label) + '</label></div>';
	}

	function renderProperties() {
		var $panel = $('#pgfw-pdf-builder-properties');
		var block = selectedBlockId ? findBlock(selectedBlockId) : null;

		if (!block) {
			$panel.html('<p class="description">Select a block on the canvas to edit its properties.</p>');
			return;
		}

		var html = '';

		if (block.type === 'text') {
			html += field('select', 'source', 'Source', block.source, [
				['static', 'Static Text'],
				['post_title', 'Post Title'],
				['post_date', 'Post Date'],
				['post_author', 'Post Author'],
				['post_excerpt', 'Post Excerpt'],
				['post_content', 'Post Content']
			]);
			if (block.source === 'static') {
				html += '<div class="pgfw-pdf-builder-field">'
					+ '<label>Text</label>'
					+ '<div class="pgfw-pdf-builder-format-row">'
					+ '<button type="button" class="pgfw-btn pgfw-btn--ghost pgfw-btn--sm" id="pgfw-format-bold"><b>B</b></button>'
					+ '<button type="button" class="pgfw-btn pgfw-btn--ghost pgfw-btn--sm" id="pgfw-format-italic"><i>I</i></button>'
					+ '</div>'
					+ '<textarea data-field="content" rows="3">' + escapeHtml(block.content) + '</textarea>'
					+ '</div>';
			}
			html += '<div class="pgfw-pdf-builder-field pgfw-pdf-builder-field--row">'
				+ '<div><label>Font size</label><input type="number" min="1" data-field="font_size" value="' + block.font_size + '" /></div>'
				+ '<div><label>Color</label><input type="text" class="pgfw-color-field" data-field="color" value="' + escapeHtml(block.color) + '" /></div>'
				+ '</div>';
			html += field('select', 'align', 'Align', block.align, [['left', 'Left'], ['center', 'Center'], ['right', 'Right']]);
			html += checkboxRow('bold', 'Bold', block.bold) + checkboxRow('italic', 'Italic', block.italic);
		} else if (block.type === 'meta') {
			var options = metaFieldOptions(currentPostType).map(function (key) {
				return [key, key];
			});
			html += field('select', 'meta_key', 'Meta Field', block.meta_key, options.length ? options : [['', 'No meta fields selected on the Meta Fields tab']]);
			html += '<div class="pgfw-pdf-builder-field"><label>Label (optional prefix)</label><input type="text" data-field="label" value="' + escapeHtml(block.label) + '" /></div>';
			html += '<div class="pgfw-pdf-builder-field pgfw-pdf-builder-field--row">'
				+ '<div><label>Font size</label><input type="number" min="1" data-field="font_size" value="' + block.font_size + '" /></div>'
				+ '<div><label>Color</label><input type="text" class="pgfw-color-field" data-field="color" value="' + escapeHtml(block.color) + '" /></div>'
				+ '</div>';
		} else if (block.type === 'image') {
			html += field('select', 'source', 'Source', block.source, [
				['featured_image', 'Featured Image'],
				['static', 'Choose Image']
			]);
			if (block.source === 'static') {
				html += '<div class="pgfw-pdf-builder-field"><button type="button" class="pgfw-btn pgfw-btn--ghost" id="pgfw-pdf-builder-choose-image">' + (param.i18n.upload_image || 'Choose Image') + '</button></div>';
			}
		}

		html += '<div class="pgfw-pdf-builder-field pgfw-pdf-builder-field--row">'
			+ '<div><label>' + (block.type === 'rectangle' ? 'Fill color' : 'Background') + '</label><input type="text" class="pgfw-color-field" data-field="background_color" value="' + escapeHtml(block.background_color) + '" /></div>'
			+ '<div><label>Border width</label><input type="number" min="0" data-field="border_width" value="' + block.border_width + '" /></div>'
			+ '</div>';
		html += '<div class="pgfw-pdf-builder-field pgfw-pdf-builder-field--row">'
			+ '<div><label>Border color</label><input type="text" class="pgfw-color-field" data-field="border_color" value="' + escapeHtml(block.border_color) + '" /></div>'
			+ '</div>';

		html += '<div class="pgfw-pdf-builder-field pgfw-pdf-builder-field--row">'
			+ '<div><label>X</label><input type="number" data-field="x" value="' + block.x + '" /></div>'
			+ '<div><label>Y</label><input type="number" data-field="y" value="' + block.y + '" /></div>'
			+ '</div>'
			+ '<div class="pgfw-pdf-builder-field pgfw-pdf-builder-field--row">'
			+ '<div><label>Width</label><input type="number" min="1" data-field="width" value="' + block.width + '" /></div>'
			+ '<div><label>Height</label><input type="number" min="1" data-field="height" value="' + block.height + '" /></div>'
			+ '</div>';

		$panel.html(html);

		$panel.find('.pgfw-color-field').wpColorPicker({
			change: function () {
				setTimeout(function () {
					$panel.find('.pgfw-color-field').each(function () {
						var $f = $(this);
						block[$f.data('field')] = $f.wpColorPicker('color');
					});
					renderCanvas();
				}, 10);
			}
		});

		$panel.find('[data-field]').on('change input', function () {
			var $f = $(this);
			var name = $f.data('field');
			var val = $f.is(':checkbox') ? $f.is(':checked') : $f.val();
			if (['x', 'y', 'width', 'height', 'font_size', 'border_width'].indexOf(name) !== -1) {
				val = parseInt(val, 10) || 0;
			}
			block[name] = val;
			if (name === 'source') {
				renderProperties();
			}
			renderCanvas();
		});

		$panel.find('#pgfw-pdf-builder-choose-image').on('click', function (e) {
			e.preventDefault();
			var frame = wp.media({
				title: param.i18n.upload_image || 'Choose Image',
				library: { type: 'image' },
				multiple: false,
				button: { text: param.i18n.use_image || 'Use Image' }
			});
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				block.content = attachment.url;
				renderCanvas();
			});
			frame.open();
		});

		$panel.find('#pgfw-format-bold').on('click', function (e) {
			e.preventDefault();
			wrapSelection($panel.find('[data-field="content"]')[0], '<strong>', '</strong>', block);
		});
		$panel.find('#pgfw-format-italic').on('click', function (e) {
			e.preventDefault();
			wrapSelection($panel.find('[data-field="content"]')[0], '<em>', '</em>', block);
		});
	}

	function wrapSelection(textarea, open, close, block) {
		if (!textarea) {
			return;
		}
		var start = textarea.selectionStart;
		var end = textarea.selectionEnd;
		var val = textarea.value;
		var wrapped = val.slice(0, start) + open + val.slice(start, end) + close + val.slice(end);
		textarea.value = wrapped;
		block.content = wrapped;
		renderCanvas();
	}

	function reorderSelected(toFront) {
		if (!selectedBlockId) {
			return;
		}
		var blocks = currentBlocks();
		var idx = -1;
		for (var i = 0; i < blocks.length; i++) {
			if (blocks[i].id === selectedBlockId) {
				idx = i;
				break;
			}
		}
		if (idx === -1) {
			return;
		}
		var block = blocks.splice(idx, 1)[0];
		if (toFront) {
			blocks.push(block);
		} else {
			blocks.unshift(block);
		}
		renderCanvas();
	}

	function duplicateSelected() {
		var block = selectedBlockId ? findBlock(selectedBlockId) : null;
		if (!block) {
			return;
		}
		blockCounter++;
		var copy = $.extend({}, block, {
			id: 'pgfw_block_' + Date.now() + '_' + blockCounter,
			x: block.x + SNAP,
			y: block.y + SNAP
		});
		currentBlocks().push(copy);
		selectedBlockId = copy.id;
		renderCanvas();
		renderProperties();
	}

	function refreshPageMetaControls() {
		var layout = currentLayout();
		$('#pgfw-pdf-builder-bg-color').val(layout.background_color || '').trigger('change');
		$('#pgfw-pdf-builder-watermark-enable').prop('checked', !!layout.watermark.enable);
		$('#pgfw-pdf-builder-watermark-text').val(layout.watermark.text || '');
		$('#pgfw-pdf-builder-watermark-color').val(layout.watermark.color || '#999999').trigger('change');
		$('#pgfw-pdf-builder-watermark-size').val(layout.watermark.font_size || 60);
		$('#pgfw-pdf-builder-watermark-opacity').val(layout.watermark.opacity != null ? layout.watermark.opacity : 0.2);
		$('#pgfw-pdf-builder-watermark-fields').toggle(!!layout.watermark.enable);
	}

	var TPL_REF_WIDTH = 794;
	var TPL_REF_HEIGHT = 1123;
	var TPL_THUMB_WIDTH = 168;

	function renderTemplateGrid() {
		var $grid = $('#pgfw-pdf-builder-template-grid');
		$grid.empty();

		var scale = TPL_THUMB_WIDTH / TPL_REF_WIDTH;
		var thumbHeight = Math.round(TPL_REF_HEIGHT * scale);

		templates.forEach(function (tpl) {
			var $card = $('<div class="pgfw-template-card"></div>');
			var $thumb = $('<div class="pgfw-template-card__thumb"></div>').css({
				width: TPL_THUMB_WIDTH + 'px',
				height: thumbHeight + 'px'
			});

			(tpl.blocks || []).forEach(function (block) {
				var $mini = $('<div class="pgfw-template-card__block pgfw-template-card__block--' + block.type + '"></div>').css({
					left: Math.round((block.x || 0) * scale) + 'px',
					top: Math.round((block.y || 0) * scale) + 'px',
					width: Math.max(2, Math.round((block.width || 0) * scale)) + 'px',
					height: Math.max(2, Math.round((block.height || 0) * scale)) + 'px'
				});

				if (block.type === 'rectangle' || block.type === 'image') {
					$mini.css('background-color', block.background_color || (block.type === 'image' ? '#cbd5e1' : '#e5e7eb'));
					if (block.border_width) {
						$mini.css('border', '1px solid ' + (block.border_color || '#000'));
					}
				} else {
					// Text / meta blocks: too small to render legible text at
					// this scale, so show a colored bar (using the block's own
					// background, or its text color at reduced opacity) that
					// still communicates where content sits and roughly how
					// prominent/colorful it is.
					$mini.css('background-color', block.background_color || block.color || '#333333');
					if (!block.background_color) {
						$mini.css('opacity', 0.55);
					}
				}

				$thumb.append($mini);
			});

			var $name = $('<div class="pgfw-template-card__name"></div>').text(tpl.name);
			$card.append($thumb).append($name);
			$card.on('click', function () {
				applyTemplate(tpl);
			});
			$grid.append($card);
		});
	}

	function applyTemplate(tpl) {
		blockCounter++;
		var blocks = (tpl.blocks || []).map(function (b) {
			return $.extend({}, b, { id: 'pgfw_block_' + Date.now() + '_' + (blockCounter++) });
		});
		currentPages()[currentPageIndex].blocks = blocks;
		currentLayout().background_color = '';
		selectedBlockId = null;
		$('#pgfw-pdf-builder-template-modal').hide();
		renderCanvas();
		renderProperties();
		refreshPageMetaControls();
	}

	function saveLayout() {
		var $status = $('#pgfw-pdf-builder-status');
		$status.text('...');

		var layout = currentLayout();

		$.post(param.ajaxurl, {
			action: 'pgfw_save_pdf_builder_layout',
			nonce: param.nonce,
			post_type: currentPostType,
			enable: $('#pgfw-pdf-builder-enable').is(':checked') ? 'yes' : 'no',
			pages: JSON.stringify(currentPages()),
			background_color: layout.background_color || '',
			watermark: JSON.stringify(layout.watermark)
		}).done(function (response) {
			if (response && response.success) {
				$status.text(param.i18n.saved || 'Layout saved.');
			} else {
				$status.text(param.i18n.save_error || 'Could not save the layout. Please try again.');
			}
		}).fail(function () {
			$status.text(param.i18n.save_error || 'Could not save the layout. Please try again.');
		}).always(function () {
			setTimeout(function () {
				$status.text('');
			}, 3000);
		});
	}

	$(function () {
		if (!$('#pgfw-pdf-builder-app').length) {
			return;
		}

		renderPageTabs();
		renderCanvas();
		renderProperties();
		renderTemplateGrid();
		refreshPageMetaControls();

		$('.pgfw-color-field').wpColorPicker();

		$('#pgfw-pdf-builder-post-type').on('change', function () {
			currentPostType = $(this).val();
			currentPageIndex = 0;
			selectedBlockId = null;
			renderPageTabs();
			renderCanvas();
			renderProperties();
			refreshPageMetaControls();
		});

		$('.pgfw-pdf-builder-add').on('click', function () {
			var type = $(this).data('type');
			var block = newBlockDefaults(type);
			currentBlocks().push(block);
			selectedBlockId = block.id;
			renderCanvas();
			renderProperties();
		});

		$('#pgfw-pdf-builder-add-page').on('click', function () {
			currentPages().push({ blocks: [] });
			currentPageIndex = currentPages().length - 1;
			selectedBlockId = null;
			renderPageTabs();
			renderCanvas();
			renderProperties();
		});

		$('#pgfw-pdf-builder-delete-page').on('click', function () {
			var pages = currentPages();
			if (pages.length <= 1) {
				window.alert('A layout needs at least one page.');
				return;
			}
			if (!window.confirm('Delete this page and everything on it?')) {
				return;
			}
			pages.splice(currentPageIndex, 1);
			currentPageIndex = Math.max(0, currentPageIndex - 1);
			selectedBlockId = null;
			renderPageTabs();
			renderCanvas();
			renderProperties();
		});

		$('#pgfw-pdf-builder-clear').on('click', function () {
			if (!window.confirm('Remove every block on this page?')) {
				return;
			}
			currentPages()[currentPageIndex].blocks = [];
			selectedBlockId = null;
			renderCanvas();
			renderProperties();
		});

		$('#pgfw-pdf-builder-duplicate').on('click', duplicateSelected);
		$('#pgfw-pdf-builder-front').on('click', function () { reorderSelected(true); });
		$('#pgfw-pdf-builder-back').on('click', function () { reorderSelected(false); });

		$('#pgfw-pdf-builder-save').on('click', saveLayout);

		$('#pgfw-pdf-builder-canvas').on('click', function (e) {
			if (e.target === this) {
				selectBlock(null);
			}
		});

		// Page background color.
		$('#pgfw-pdf-builder-bg-color').wpColorPicker({
			change: function () {
				setTimeout(function () {
					currentLayout().background_color = $('#pgfw-pdf-builder-bg-color').wpColorPicker('color') || '';
					renderCanvas();
				}, 10);
			},
			clear: function () {
				currentLayout().background_color = '';
				renderCanvas();
			}
		});

		// Watermark controls.
		$('#pgfw-pdf-builder-watermark-enable').on('change', function () {
			currentLayout().watermark.enable = $(this).is(':checked');
			$('#pgfw-pdf-builder-watermark-fields').toggle(currentLayout().watermark.enable);
			renderCanvas();
		});
		$('#pgfw-pdf-builder-watermark-text').on('input', function () {
			currentLayout().watermark.text = $(this).val();
			renderCanvas();
		});
		$('#pgfw-pdf-builder-watermark-color').wpColorPicker({
			change: function () {
				setTimeout(function () {
					currentLayout().watermark.color = $('#pgfw-pdf-builder-watermark-color').wpColorPicker('color') || '#999999';
					renderCanvas();
				}, 10);
			}
		});
		$('#pgfw-pdf-builder-watermark-size').on('input', function () {
			currentLayout().watermark.font_size = parseInt($(this).val(), 10) || 60;
			renderCanvas();
		});
		$('#pgfw-pdf-builder-watermark-opacity').on('input', function () {
			currentLayout().watermark.opacity = parseFloat($(this).val());
			renderCanvas();
		});

		// Template gallery modal.
		$('#pgfw-pdf-builder-open-templates').on('click', function () {
			$('#pgfw-pdf-builder-template-modal').show();
		});
		$('#pgfw-pdf-builder-close-templates, .pgfw-pdf-builder-modal__backdrop').on('click', function () {
			$('#pgfw-pdf-builder-template-modal').hide();
		});
	});
})(jQuery);
