/**
 * Button styles
 */

const buttonHoverStyles = {
	color: {
		background: 'var:preset|color|primary-800',
	},
};

export default {
	border: {
		radius: 'var:custom|border-radius|large',
	},
	typography: {
		fontSize: 'var:preset|font-size|sm',
		lineHeight: '1.1',
	},
	color: {
		background: 'var:preset|color|primary',
		text: 'var:preset|color|neutral-100',
	},
	':hover': buttonHoverStyles,
	':focus': buttonHoverStyles,
	':active': buttonHoverStyles,
};
