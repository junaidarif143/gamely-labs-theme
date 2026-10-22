/* Gamely theme: menus and the "On this page" list. */
(function () {
	'use strict';

	var header = document.getElementById('g-header');
	var burger = document.getElementById('g-burger');

	/* ---- Dropdowns ---- */
	var items = header ? header.querySelectorAll('.g-nav__item') : [];
	function closeAll(except) {
		Array.prototype.forEach.call(items, function (item) {
			if (item === except) return;
			item.classList.remove('is-open');
			var b = item.querySelector('.g-nav__toggle');
			if (b) b.setAttribute('aria-expanded', 'false');
		});
	}
	Array.prototype.forEach.call(items, function (item) {
		var btn = item.querySelector('.g-nav__toggle');
		if (!btn) return;
		btn.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = !item.classList.contains('is-open');
			closeAll(item);
			item.classList.toggle('is-open', open);
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});
	document.addEventListener('click', function (e) {
		if (header && !header.contains(e.target)) {
			closeAll();
			setMenu(false);
		}
	});
	document.addEventListener('keydown', function (e) {
		if (e.key !== 'Escape') return;
		var openItem = header && header.querySelector('.g-nav__item.is-open');
		closeAll();
		if (openItem) {
			var b = openItem.querySelector('.g-nav__toggle');
			if (b) b.focus();
		} else if (header && header.classList.contains('is-menu-open')) {
			setMenu(false);
			burger.focus();
		}
	});

	/* ---- Mobile menu ---- */
	function setMenu(open) {
		if (!header || !burger) return;
		header.classList.toggle('is-menu-open', open);
		burger.setAttribute('aria-expanded', open ? 'true' : 'false');
		burger.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		burger.querySelector('.g-burger__open').hidden = open;
		burger.querySelector('.g-burger__close').hidden = !open;
	}
	if (burger) {
		burger.addEventListener('click', function (e) {
			e.stopPropagation();
			setMenu(!header.classList.contains('is-menu-open'));
		});
	}
	// Close the mobile menu when an in-page link is followed.
	if (header) {
		header.addEventListener('click', function (e) {
			if (e.target.closest('a')) setMenu(false);
		});
	}

	/* ---- On this page ---- */
	var article = document.getElementById('g-article');
	var toc = document.getElementById('g-toc');
	if (article && toc) {
		var used = {};
		var heads = article.querySelectorAll('h2');
		var count = 0;
		Array.prototype.forEach.call(heads, function (h) {
			if (h.closest('.gl-tool')) return; // skip headings inside calculators
			var text = h.textContent.trim();
			if (!text) return;
			if (!h.id) {
				var base = text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '') || 'section';
				var id = base, n = 2;
				while (used[id] || document.getElementById(id)) id = base + '-' + n++;
				h.id = id;
			}
			used[h.id] = true;
			var li = document.createElement('li');
			var a = document.createElement('a');
			a.href = '#' + h.id;
			a.textContent = text;
			li.appendChild(a);
			toc.appendChild(li);
			count++;
		});
		if (count > 1) document.getElementById('g-toc-panel').hidden = false;
	}
})();
