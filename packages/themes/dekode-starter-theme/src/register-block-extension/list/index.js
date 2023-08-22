/**
 * Internal dependencies
 */
import { registerBlockExtension } from '../index';

import { BlockEdit } from './edit';

function generateClassNames(attributes) {
	const { type } = attributes;

	if ('checklist' === type) {
		return 'is-type-checklist';
	}
}

registerBlockExtension('core/list', {
	extensionName: 'checklist-type',
	classNameGenerator: generateClassNames,
	Edit: BlockEdit,
});
