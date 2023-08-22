/**
 * Internal dependencies
 */
import { registerBlockExtension } from '../index';

import { BlockEdit } from './edit';

const BUTTON_STYLE_ATTRIBUTES = {
	hasArrow: {
		type: 'boolean',
		default: false,
	},
	backGroundColor: {
		type: 'string',
		default: 'purple',
	},
};

/**
 * generateClassNames
 *
 * a function to generate the new className string that should get added to
 * the wrapping element of the block.
 *
 * @param {Object} attributes block attributes
 * @return {string}
 */
function generateClassNames(attributes) {
	const { hasArrow, backgroundColor } = attributes;

	const backgroundColorClassName = backgroundColor
		? `has-${backgroundColor}-background-color`
		: 'has-purple-background-color';
	const hasArrowClassName = hasArrow ? 'has-arrow' : '';

	return `${backgroundColorClassName} ${hasArrowClassName}`;
}

/*
registerBlockExtension('core/button', {
	extensionName: 'button-styles',
	attributes: BUTTON_STYLE_ATTRIBUTES,
	classNameGenerator: generateClassNames,
	Edit: BlockEdit,
});
*/
