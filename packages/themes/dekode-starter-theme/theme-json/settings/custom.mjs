/**
 * External dependencies
 */
import clamp from 'css-clamp';

/**
 * Custom settings
 */
export default {
	t2: {
		minHeight: {
			none: '5rem',
			small: '15rem',
			medium: '25rem',
			large: '35rem',
		},
	},
	borderRadius: {
		small: '0.25rem',
		normal: '0.5rem',
		large: clamp(36, 48),
	},
	lineHeight: {
		small: '1.1',
		medium: '1.3',
		normal: '1.5',
	},
};
