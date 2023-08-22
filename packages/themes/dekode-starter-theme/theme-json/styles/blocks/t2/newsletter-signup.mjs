/**
 * Newsletter Signup styles
 */
export default {
	border: {
		radius: 'var:custom|border-radius|normal',
	},
	color: {
		background: 'var:preset|color|primary',
		text: 'var:preset|color|white',
	},
	spacing: {
		padding: {
			top: 'var:preset|spacing|md',
			right: 'var:preset|spacing|md',
			bottom: 'var:preset|spacing|md',
			left: 'var:preset|spacing|md',
		},
	},
	elements: {
		button: {
			color: {
				background: 'var:preset|color|white',
				text: 'var:preset|color|primary',
			},
			':hover': {
				color: {
					background: 'var:preset|color|primary-100',
				},
			},
			':focus': {
				color: {
					background: 'var:preset|color|primary-100',
				},
			},
		},
		h2: {
			typography: {
				fontSize: 'var:preset|font-size|xl',
			},
			spacing: {
				margin: {
					top: '0',
					bottom: '0.5rem',
				},
			},
		},
	},
};
