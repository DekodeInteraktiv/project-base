/**
 * WordPress dependencies
 */
import domReady from '@wordpress/dom-ready';

function onExampleBlockReady(block) {
	// eslint-disable-next-line no-console
	console.log(block);
}

function init() {
	document
		.querySelectorAll('.wp-block-dekode-example-block')
		.forEach(onExampleBlockReady);
}

domReady(init);
