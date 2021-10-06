/* eslint-disable no-use-before-define */
const toggleButton = document.querySelector('.site-header--search--toggle');
const formContainer = document.getElementById('site-search');
const input = document.querySelector('.site-search .search-field');

function hide() {
	toggleButton.setAttribute('aria-expanded', 'false');
	formContainer.setAttribute('aria-hidden', 'true');

	document.body.classList.remove('site-search__is-visible');

	document.removeEventListener('mouseup', onMouseUp, {
		capture: true,
	});

	document.removeEventListener('keydown', handleKeyDown);
}

function handleKeyDown(event) {
	const { keyCode } = event;

	if (keyCode === 27) {
		hide();
	}
}

function onMouseUp(event) {
	const isDescendantOfForm = formContainer.contains(event.target);
	const isDescendantOfButton = toggleButton.contains(event.target);

	if (!isDescendantOfForm && !isDescendantOfButton) {
		hide();
	}
}

function show() {
	toggleButton.setAttribute('aria-expanded', 'true');
	formContainer.setAttribute('aria-hidden', 'false');

	document.body.classList.add('site-search__is-visible');

	document.addEventListener('mouseup', onMouseUp, {
		capture: true,
	});

	document.addEventListener('keydown', handleKeyDown);

	input.focus();
	input.select();
}

function toggle() {
	if (!document.body.classList.contains('site-search__is-visible')) {
		show();
	} else {
		hide();
	}
}

if (toggleButton) {
	toggleButton.addEventListener('click', toggle);
}
