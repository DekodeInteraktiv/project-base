/**
 * Search styles
 */
export default {
	css: `
		&.wp-block-search__button-inside .wp-block-search__inside-wrapper {
			border: 0.125rem solid var(--wp--preset--color--primary-100);
			border-radius: var(--wp--custom--border-radius--normal);
			padding: 0;
		}

		&.wp-block-search__button-inside .wp-block-search__inside-wrapper:has(input[type="search"]:is(:focus-visible)) {
			border-color: var(--wp--preset--color--primary);
		}

		&.wp-block-search__button-inside .wp-block-search__inside-wrapper input[type="search"] {
			border: 0;
		}

		&.wp-block-search__button-inside .wp-block-search__inside-wrapper .wp-block-search__button {
			margin-top: 0.25rem;
			margin-bottom: 0.25rem;
			margin-right: 0.25rem;
			margin-left: 0;
			padding: 0.667em 1em;
		}
	`,
	elements: {
		button: {
			border: {
				radius: 'var:custom|border-radius|small',
			},
		},
	},
};
