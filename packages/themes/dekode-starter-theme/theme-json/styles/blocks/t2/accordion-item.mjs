/**
 * T2 Accordion Item
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
		--t2-accordion-typography-heading-line-height: var(--theme--line-height--md);
		--t2-accordion-item-padding: var(--wp--preset--spacing--md);
		--t2-accordion-item-open-gap: var(--wp--preset--spacing--xs);

		&:focus { ${hoverStyle} }
		&:focus-within { ${hoverStyle} }
		&:hover { ${hoverStyle} }
	`,
};
