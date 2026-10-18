(function ($) {
	var $tabs = $('.orpitey-tabs a');
	var $panels = $('.orpitey-panel');
	var $active = $('#orpitey-active-tab');

	function activate(id) {
		$tabs.removeClass('is-active');
		$tabs.filter('[data-tab="' + id + '"]').addClass('is-active');
		$panels.removeClass('is-active');
		$panels.filter('[data-panel="' + id + '"]').addClass('is-active');
		if ($active.length) {
			$active.val(id);
		}
	}

	$tabs.on('click', function (event) {
		event.preventDefault();
		activate($(this).data('tab'));
	});

	function bindRepeater($repeater) {
		if ($repeater.data('orpiteyBound')) {
			return;
		}
		$repeater.data('orpiteyBound', 1);

		var $list = $repeater.children('.orpitey-repeater__list');
		var $add = $repeater.children('.orpitey-repeater__add');
		var max = parseInt($repeater.attr('data-max'), 10) || 12;
		var label = $repeater.attr('data-label') || '';
		var token = $repeater.attr('data-token') || '__i__';
		var templateHtml = '';
		var templateId = $repeater.attr('data-template');
		var templateNode = templateId ? document.getElementById(templateId) : null;

		if (templateNode) {
			templateHtml = templateNode.innerHTML;
		} else if ($repeater.children('template')[0]) {
			templateHtml = $repeater.children('template')[0].innerHTML;
		}

		function reindex() {
			$list.children('.orpitey-repeater__row').each(function (i) {
				var $row = $(this);
				$row.children('.orpitey-repeater__head').children('.orpitey-repeater__label').text(label + ' ' + (i + 1));
				$row.find('[name]').each(function () {
					if (token === '__i__') {
						this.name = this.name.replace(/\[(?:\d+|__i__)\]/, '[' + i + ']');
					} else {
						this.name = this.name.replace(new RegExp('\\[(?:\\d+|' + token + ')\\](\\[[^\\]]+\\])$'), '[' + i + ']$1');
					}
				});
			});
			$add.prop('disabled', $list.children('.orpitey-repeater__row').length >= max);
		}

		$add.on('click', function (event) {
			event.preventDefault();
			if (!templateHtml || $list.children('.orpitey-repeater__row').length >= max) {
				return;
			}
			var html = templateHtml.split(token).join(String($list.children('.orpitey-repeater__row').length));
			var $nodes = $(html);
			$list.append($nodes);
			$nodes.find('.orpitey-repeater').each(function () {
				bindRepeater($(this));
			});
			$nodes.find('.orpitey-image-field').each(function () {
				bindImageField($(this));
			});
			reindex();
		});

		$list.on('click', '.orpitey-repeater__remove', function (event) {
			var $row = $(this).closest('.orpitey-repeater__row');
			if ($row.parent()[0] !== $list[0]) {
				return;
			}
			event.preventDefault();
			event.stopPropagation();
			$row.remove();
			reindex();
		});

		$list.children('.orpitey-repeater__row').find('.orpitey-repeater').each(function () {
			bindRepeater($(this));
		});
		reindex();
	}

	$('.orpitey-repeater').each(function () {
		bindRepeater($(this));
	});

	function bindImageField($field) {
		if ($field.data('orpiteyImage')) {
			return;
		}
		$field.data('orpiteyImage', 1);

		var $input = $field.find('input[type="hidden"]');
		var $preview = $field.find('img');
		var frame;

		function setImage(url) {
			$input.val(url);
			if (url) {
				$preview.attr('src', url);
				$field.removeClass('is-empty');
			} else {
				$preview.attr('src', '');
				$field.addClass('is-empty');
			}
		}

		$field.on('click', '.orpitey-image-select', function (event) {
			event.preventDefault();
			if (frame) {
				frame.open();
				return;
			}
			frame = wp.media({
				title: 'Choose image',
				button: { text: 'Use this image' },
				multiple: false,
				library: { type: 'image' }
			});
			frame.on('select', function () {
				var attachment = frame.state().get('selection').first().toJSON();
				setImage(attachment.url || '');
			});
			frame.open();
		});

		$field.on('click', '.orpitey-image-remove', function (event) {
			event.preventDefault();
			setImage('');
		});
	}

	$('.orpitey-image-field').each(function () {
		bindImageField($(this));
	});

	var $layout = $('.orpitey-layout');

	function reindexLayout() {
		var $rows = $layout.children('.orpitey-layout__row');
		$rows.each(function (i) {
			$(this).find('[name]').each(function () {
				this.name = this.name.replace(/home_layout\[\d+\]/, 'home_layout[' + i + ']');
			});
			$(this).find('[data-dir="-1"]').prop('disabled', i === 0);
			$(this).find('[data-dir="1"]').prop('disabled', i === $rows.length - 1);
		});
	}

	if ($layout.length && $.fn.sortable) {
		$layout.sortable({
			handle: '.orpitey-layout__handle',
			items: '.orpitey-layout__row',
			axis: 'y',
			cancel: 'input,textarea,button,select,option',
			placeholder: 'orpitey-layout__placeholder',
			forcePlaceholderSize: true,
			update: reindexLayout
		});
	}

	$layout.on('click', '.orpitey-layout__move', function (event) {
		event.preventDefault();
		var $row = $(this).closest('.orpitey-layout__row');
		var dir = parseInt($(this).attr('data-dir'), 10);
		if (dir < 0) {
			var $prev = $row.prev('.orpitey-layout__row');
			if ($prev.length) {
				$prev.before($row);
			}
		} else {
			var $next = $row.next('.orpitey-layout__row');
			if ($next.length) {
				$next.after($row);
			}
		}
		reindexLayout();
	});

	$layout.on('click', '.orpitey-layout__toggle', function (event) {
		event.preventDefault();
		var $button = $(this);
		var $row = $button.closest('.orpitey-layout__row');
		var hidden = !$row.hasClass('is-hidden');
		$row.toggleClass('is-hidden', hidden);
		$row.find('input[name$="[hidden]"]').val(hidden ? '1' : '0');
		$button.attr('aria-pressed', hidden ? 'true' : 'false');
		$button.attr('aria-label', hidden ? 'Show' : 'Hide');
		$button.find('.dashicons').toggleClass('dashicons-hidden', hidden).toggleClass('dashicons-visibility', !hidden);
	});
})(jQuery);
