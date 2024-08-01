/**
 * External dependencies
 */
import clamp from 'css-clamp';

/**
 * Font size settings
 */
export default [
	{
		name: 'XXS',
		slug: 'xxs',
		size: clamp(12, 14),
	},
	{
		name: 'XS',
		slug: 'xs',
		size: clamp(14, 16),
	},
	{
		name: 'Small',
		slug: 'sm',
		size: clamp(16, 18),
	},
	{
		name: 'Normal',
		slug: 'normal',
		size: clamp(18, 20),
	},
	{
		name: 'Medium',
		slug: 'md',
		size: clamp(20, 22),
	},
	{
		name: 'Large',
		slug: 'lg',
		size: clamp(22, 24),
	},
	{
		name: 'XL',
		slug: 'xl',
		size: clamp(24, 28),
	},
	{
		name: 'XXL',
		slug: 'xxl',
		size: clamp(36, 56),
	},
];
