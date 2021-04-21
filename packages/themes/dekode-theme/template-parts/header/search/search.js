/**
 * External dependencies
 */
import { addEventListener } from 'consolidated-events';

/**
 * Elements
 */
const toggleButton = document.querySelector( '.site-header--search--toggle' );
const formContainer = document.getElementById( 'site-search' );
const input = document.querySelector( '.site-search .search-field' );

let removeMouseUp, removeKeyDown;

function hide() {
	toggleButton.setAttribute( 'aria-expanded', 'false' );
	formContainer.setAttribute( 'aria-hidden', 'true' );

	document.body.classList.remove( 'site-search__is-visible' );

	removeMouseUp();
	removeKeyDown();
	removeMouseUp = null;
	removeKeyDown = null;
}

function handleKeyDown( event ) {
	const { keyCode } = event;

	if ( keyCode === 27 ) {
		hide();
	}
}

function onMouseUp( event ) {
	const isDescendantOfForm = formContainer.contains( event.target );
	const isDescendantOfButton = toggleButton.contains( event.target );

	if ( ! isDescendantOfForm && ! isDescendantOfButton ) {
		hide();
	}
}

function show() {
	toggleButton.setAttribute( 'aria-expanded', 'true' );
	formContainer.setAttribute( 'aria-hidden', 'false' );

	document.body.classList.add( 'site-search__is-visible' );
	removeMouseUp = addEventListener( document, 'mouseup', onMouseUp, { capture: true } );
	removeKeyDown = addEventListener( document, 'keydown', handleKeyDown );

	input.focus();
	input.select();
}

function toggle( event ) {
	if ( ! document.body.classList.contains( 'site-search__is-visible' ) ) {
		show( event );
	} else {
		hide();
	}
}

if ( toggleButton ) {
	addEventListener( toggleButton, 'click', toggle );
}
