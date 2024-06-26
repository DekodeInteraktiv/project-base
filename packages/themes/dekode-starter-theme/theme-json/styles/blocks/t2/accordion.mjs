/**
 * T2 Accordion
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
		--t2-accordion-color-icon: var(--wp--preset--color--primary);
		--t2-accordion-typography-heading-font-family: var(--wp--preset--font-family--secondary);
		--t2-accordion-typography-heading-font-size: var(--wp--preset--font-size--lg);
		--t2-accordion-typography-heading-line-height: var(--wp--custom--line-height--medium);
		--t2-accordion-item-padding: var(--wp--preset--spacing--md);
		--t2-accordion-item-open-gap: var(--wp--preset--spacing--xs);

		& .t2-accordion-item:focus { ${hoverStyle} }
		& .t2-accordion-item:focus-within { ${hoverStyle} }
		& .t2-accordion-item:hover { ${hoverStyle} }
	`,
};
