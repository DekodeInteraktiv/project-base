/**
 * Image styles
 */
export default {
	border: {
		radius: 'var:custom|border-radius|normal',
	},
	css: `
		&.alignfull img {
			border-radius: 0;
		}

		& .wp-element-caption {
			margin-left: auto;
			margin-right: auto;
			max-width: var(--wp--style--global--wide-size);
			text-align: left;
		}

		&.alignfull .wp-element-caption {
			width: calc(100% - var(--wp--style--root--padding-right) - var(--wp--style--root--padding-left));
		}
	`,
};
