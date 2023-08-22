/**
 * Font family settings
 */
export default [
	{
		slug: 'primary',
		name: 'Public Sans',
		fontFamily: '"Public Sans", Arial, "Helvetica Neue", Helvetica, sans-serif',
		fontFace: [
			{
				fontFamily: 'Public Sans',
				fontStyle: 'normal',
				fontWeight: '300 700',
				fontDisplay: 'swap',
				src: ['file:./assets/fonts/public-sans/public-sans-300-700.woff2'],
			},
			{
				fontFamily: 'Public Sans',
				fontStyle: 'italic',
				fontWeight: '300 700',
				fontDisplay: 'swap',
				src: ['file:./assets/fonts/public-sans/public-sans-300-700-italic.woff2'],
			},
		],
	},
	{
		slug: 'secondary',
		name: 'Poppins',
		fontFamily: 'Poppins, Arial, "Helvetica Neue", Helvetica, sans-serif',
		fontFace: [
			{
				src: ['file:./assets/fonts/poppins/poppins-v20-latin-regular.woff2'],
				fontWeight: '400',
				fontStyle: 'regular',
				fontFamily: 'Poppins',
			},
			{
				src: ['file:./assets/fonts/poppins/poppins-v20-latin-600.woff2'],
				fontWeight: '600',
				fontStyle: 'semibold',
				fontFamily: 'Poppins',
			},
		],
	},
];
