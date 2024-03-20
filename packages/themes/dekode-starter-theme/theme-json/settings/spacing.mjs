/**
 * External dependencies
 */
import clamp from 'css-clamp';

/**
 * Spacing settings
 */
export default {
	margin: false,
	padding: false,
	units: ['%', 'px', 'em', 'rem', 'vh', 'vw'],
	customSpacingSize: false,
	spacingSizes: [
		{
			size: '0.5rem',
			slug: 'xs',
			name: 'XS',
		},
		{
			size: clamp(12, 16),
			slug: 'sm',
			name: 'Small',
		},
		{
			size: clamp(16, 32),
			slug: 'md',
			name: 'Medium',
		},
		{
			size: clamp(32, 48),
			slug: 'lg',
			name: 'Large',
		},
		{
			size: clamp(48, 120),
			slug: 'xl',
			name: 'XL',
		},
	],
};
