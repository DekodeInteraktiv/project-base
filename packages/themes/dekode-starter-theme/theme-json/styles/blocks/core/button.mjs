/**
 * Button styles
 */
export default {
	css: `
		transition-property: background-color, border-color, color;
		transition-duration: 325ms;
		transition-timing-function: ease-in-out;
	`,
	variations: {
		outline: {
			color: {
				text: 'var:preset|color|primary',
			},
		},
	},
};
