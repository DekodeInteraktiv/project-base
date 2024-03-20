/**
 * T2 FAQ
 */

// Shared hover, focus, and focus-within styles
const hoverStyle = 'background-color: var(--wp--preset--color--primary-100);';

export default {
	border: {
		radius: 'var:custom|border-radius|normal',
		width: '0',
	},
	color: {
		background: 'var:preset|color|neutral-200',
	},
	css: `
		--t2-faq-color-icon: var(--wp--preset--color--primary);
		--t2-faq-typography-heading-font-family: var(--wp--preset--font-family--secondary);
		--t2-faq-typography-heading-font-size: var(--wp--preset--font-size--lg);
		--t2-faq-typography-heading-line-height: var(--wp--custom--line-height--medium);

		& .t2-faq-item:focus { ${hoverStyle} }
		& .t2-faq-item:focus-within { ${hoverStyle} }
		& .t2-faq-item:hover { ${hoverStyle} }

		& .t2-faq-item {
			padding: var(--wp--preset--spacing--sm);
		}
	`,
};
