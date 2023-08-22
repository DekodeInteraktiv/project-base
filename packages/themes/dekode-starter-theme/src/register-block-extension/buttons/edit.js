/**
 * WordPress dependencies
 */
import { InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	ToggleControl,
	ColorPalette,
	BaseControl,
} from '@wordpress/components';

/**
 * BlockEdit
 *
 * a react component that will get mounted in the Editor when the block is
 * selected. It is reccomended to use Slots like `BlockControls` or `InspectorControls`
 * in here to put settings into the blocks toolbar or sidebar.
 *
 * @param {Object} props block props
 * @return {JSX}
 */
export function BlockEdit(props) {
	const { attributes, setAttributes } = props;
	const { hasArrow = false, backgroundColor = 'purple' } = attributes;

	const COLOR_OPTIONS = [
		{
			name: 'Purple',
			slug: 'purple',
			color: '#22209A',
		},
		{
			name: 'Lilac',
			slug: 'lilac',
			color: '#9190CD',
		},
		{
			name: 'White',
			slug: 'white',
			color: '#fffff',
		},
	];

	return (
		<InspectorControls>
			<PanelBody title="Button styles">
				<ToggleControl
					label="Has Arrow"
					help={hasArrow ? 'Has arrow.' : 'No arrow.'}
					checked={hasArrow}
					onChange={(value) => setAttributes({ hasArrow: value })}
				/>
				<BaseControl label="Background Color">
					<ColorPalette
						colors={COLOR_OPTIONS}
						disableCustomColors
						value={
							COLOR_OPTIONS.find(
								(item) => item.slug === backgroundColor
							).color
						}
						onChange={(value) => {
							const colorName = COLOR_OPTIONS.find(
								(item) => item.color === value
							).slug;
							setAttributes({
								backgroundColor: colorName,
							});
						}}
					/>
				</BaseControl>
			</PanelBody>
		</InspectorControls>
	);
}
