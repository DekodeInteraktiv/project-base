/**
 * Post term styles
 */
export default {
	css: `
		& span.wp-block-post-terms__separator {
			display: none;
		}
	`,
	typography: {
		fontSize: 'var:preset|font-size|xxs',
	},
	elements: {
		link: {
			border: {
				radius: 'var:custom|border-radius|large',
			},
			color: {
				background: 'var:preset|color|primary-100',
			},
			typography: {
				textDecoration: 'none',
				textTransform: 'uppercase',
			},
			spacing: {
				margin: {
					right: 'var:preset|spacing|xs',
				},
				padding: {
					bottom: 'var:preset|spacing|xs',
					left: 'var:preset|spacing|sm',
					right: 'var:preset|spacing|sm',
					top: 'var:preset|spacing|xs',
				},
			},
		},
	},
};
