/**
 * WordPress dependencies
 */
import { addAction } from '@wordpress/hooks';

/**
 * When Query block results are refreshed, update the search input term in the h1 (search page).
 */
addAction(
	'T2.Query.ResultsRefreshed',
	'Theme.Query.ResultsRefreshed',
	($block) => {
		const $input = $block.querySelector('input[name=search],input[name=s]');

		if ($input) {
			const $elements = document.querySelectorAll(
				'[data-search-keyword]'
			);

			$elements.forEach(($element) => {
				$element.textContent = $input.value;
			});
		}

		// Scroll to top of the block.
		$block.scrollIntoView({
			behavior: 'smooth',
		});
	}
);
