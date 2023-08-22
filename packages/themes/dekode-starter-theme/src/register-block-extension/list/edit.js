/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { BlockControls } from '@wordpress/block-editor';
import { ToolbarButton } from '@wordpress/components';
import { check } from '@wordpress/icons';
/**
 * BlockEdit
 * A React component that will get mounted in the Editor when the block is
 * selected. It is reccomended to use Slots like `BlockControls` or `InspectorControls`
 * in here to put settings into the blocks toolbar or sidebar.
 *
 * @param {Object} props block props
 */
export function BlockEdit(props) {
	const { attributes, setAttributes } = props;
	const { type, ordered } = attributes;

	if (ordered && type === 'checklist') {
		setAttributes({ type: '' });
	}

	return (
		<BlockControls>
			<ToolbarButton
				icon={check}
				label={__('Checklist', 'starter')}
				onClick={() => {
					if (type === 'checklist') {
						setAttributes({ type: '' });
					} else {
						setAttributes({ type: 'checklist' });
						setAttributes({ ordered: false });
					}
				}}
				isActive={type === 'checklist'}
			/>
		</BlockControls>
	);
}
