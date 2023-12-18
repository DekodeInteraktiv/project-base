/**
 * Header styles
 */
export default {
	color: {
		text: 'var:preset|color|primary',
	},
	css: `
		--t2-header--submenu-open-background: var(--wp--preset--color--primary-100);
	`,
	typography: {
		fontSize: 'var:preset|font-size|sm',
	},
	elements: {
		link: {
			typography: {
				textDecoration: 'none',
			},
			':hover': {
				typography: {
					textDecoration: 'underline',
				},
			},
		},
	},
};
