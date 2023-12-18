(function () {
	/**
	 * Add a fake input span to checkboxes and radios.
	 */
	const addFakeInputs = () => {
		// Find all checkboxes and radio buttons.
		const $checkboxesRadios = document.querySelectorAll('input[type="checkbox"], input[type="radio"]');

		// Loop through each checkbox and radio button and add a fake element next to it, then add a class to the input.
		$checkboxesRadios.forEach(($checkboxRadio) => {
			// If it already has the "faked" class, bail early.
			if ($checkboxRadio.classList.contains('faked')) {
				return;
			}

			const $fakeElement = document.createElement('span');
			$fakeElement.classList.add('fake-input');
			$fakeElement.classList.add(`type-${$checkboxRadio.type}`);
			$checkboxRadio.parentNode.insertBefore($fakeElement, $checkboxRadio.nextSibling);
			$checkboxRadio.classList.add('faked');
		});

		// Find all select fields.
		const $selects = document.querySelectorAll('select:not([hidden]');

		// Loop through the select fields and wrap them in a "fake-input-container" div only if it doesn't already have it.
		$selects.forEach(($select) => {
			if ($select.parentNode.classList.contains('fake-input-container')) {
				return;
			}

			const $fakeInputContainer = document.createElement('div');
			$fakeInputContainer.classList.add('fake-input-container');
			$fakeInputContainer.classList.add('type-select');
			$select.parentNode.insertBefore($fakeInputContainer, $select);
			$fakeInputContainer.appendChild($select);
		});
	};

	/**
	 * When clicking on a fake input radio/checkbox, trigger the click on its corresponding input.
	 */
	const handleFakeInputsClick = () => {
		const $fakeInputs = document.querySelectorAll('.fake-input');

		$fakeInputs.forEach(($fakeInput) => {
			$fakeInput.addEventListener('click', (e) => {
				e.target.previousSibling.click();
			});
		});
	};

	/**
	 * Initiliaze everything when DOM is ready.
	 */
	document.addEventListener('DOMContentLoaded', () => {
		addFakeInputs();
		handleFakeInputsClick();
	});
})();
