/**
 * External dependencies
 */
import { bench, describe } from 'vitest';

const attributes = {
	align: 'wide',
	mediaId: 42,
	mediaUrl: 'https://example.com/image.jpg',
	content: '<p>Lorem ipsum dolor sit amet.</p>',
	style: { spacing: { padding: { top: '1rem', bottom: '1rem' } } },
	lock: { move: false, remove: false },
};

describe('deep clone block attributes', () => {
	bench('structuredClone', () => {
		structuredClone(attributes);
	});

	bench('JSON round-trip', () => {
		JSON.parse(JSON.stringify(attributes));
	});
});
