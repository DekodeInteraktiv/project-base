/**
 * Infobox styles
 */
export default {
	border: {
		radius: 'var:custom|border-radius|small',
	},
	color: {
		background: 'var:preset|color|primary-100',
	},
	spacing: {
		padding: {
			bottom: 'var:preset|spacing|md',
			left: 'var:preset|spacing|md',
			right: 'var:preset|spacing|md',
			top: 'var:preset|spacing|md',
		},
	},
	variations: {
		tip: {
			color: {
				background: 'var:preset|color|primary-100',
			},
		},
		warning: {
			border: {
				width: '2px',
				style: 'solid',
				color: 'var:preset|color|info-attention',
			},
			color: {
				background: 'transparent',
			},
		},
		error: {
			border: {
				width: '2px',
				style: 'solid',
				color: 'var:preset|color|info-error',
			},
			color: {
				background: 'transparent',
				text: 'inherit',
			},
		},
	},
	css: `
		& .t2-infobox__icon {
			color: var(--wp--preset--color--primary);
			margin-right: var(--wp--preset--spacing--md);
		}

		&.is-style-error .t2-infobox__icon {
			color: var(--wp--preset--color--info-error);
		}

		&.is-style-warning .t2-infobox__icon {
			color: var(--wp--preset--color--info-attention);
		}
	`,
};
