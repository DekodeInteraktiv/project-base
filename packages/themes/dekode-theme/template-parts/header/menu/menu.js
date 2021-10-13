/**
 * Elements
 */
const toggleButton = document.querySelector('.site-header--nav-toggle');

function toggle() {
	document.body.classList.toggle('main-navigation__is-visible');
}

if (toggleButton) {
	toggleButton.addEventListener('click', toggle);
}
