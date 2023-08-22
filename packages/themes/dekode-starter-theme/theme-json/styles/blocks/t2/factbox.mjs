/**
 * Factbox styles
 */
export default {
	border: {
		width: '0',
		radius: 'var:custom|border-radius|normal',
	},
	color: {
		background: 'var:preset|color|neutral-200',
	},
	css: `
		--t2-factbox-max-height: calc(var(--wp--preset--spacing--md) + 12rem);
	`,
	spacing: {
		padding: {
			top: 'var:preset|spacing|md',
			left: 'var:preset|spacing|md',
			right: 'var:preset|spacing|md',
			bottom: 'var:preset|spacing|md',
		},
	},
};
