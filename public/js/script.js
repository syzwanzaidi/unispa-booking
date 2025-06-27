document.addEventListener('DOMContentLoaded', function () {
    const packageItemsContainer = document.getElementById('packageItemsContainer');
    const addPackageItemButton = document.getElementById('addPackageItem');

    // Data passed from Blade for initial setup
    const oldBookingItems = window.oldBookingItems || [];
    const selectedInitialPackageId = window.selectedInitialPackageId;

    // Counter for the next available item index
    let nextItemIndex = -1;

    // Creates and appends a new package item to the form
    function createAndAppendPackageItem(initialData = null) {
        nextItemIndex++; // Increment index for the new item

        const originalTemplate = document.querySelector('.package-item-template');
        if (!originalTemplate) {
            console.error("Error: '.package-item-template' not found.");
            return;
        }

        const newItem = originalTemplate.cloneNode(true); // Deep clone the template

        // Update input names with the new index and set values
        newItem.querySelectorAll('select, input, textarea').forEach(input => {
            const oldName = input.name;
            if (oldName) {
                const fieldNameMatch = oldName.match(/items\[\d+\]\[(.*)\]/);
                const fieldName = fieldNameMatch ? fieldNameMatch[1] : '';

                input.name = `items[${nextItemIndex}][${fieldName}]`;

                if (initialData && initialData[fieldName] !== undefined) {
                    input.value = initialData[fieldName];
                } else {
                    // Reset to default or empty for new items
                    input.value = (input.type === 'number' && fieldName === 'item_pax') ? 1 : '';
                    if (input.tagName === 'SELECT' && input.querySelector('option[value=""]')) {
                        input.value = '';
                    }
                }
            }
        });

        // Attach click listener for the remove button
        const removeButton = newItem.querySelector('.remove-item');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                newItem.remove();
                updateItemNumbersAndVisibility();
            });
        }

        // Pre-select package for the first item if initial ID is provided (from 'Book Now' button)
        if (initialData === null && nextItemIndex === 0 && selectedInitialPackageId) {
            const packageSelect = newItem.querySelector('.package-select');
            if (packageSelect) {
                packageSelect.value = selectedInitialPackageId;
            }
        }

        packageItemsContainer.appendChild(newItem);
        updateItemNumbersAndVisibility();
    }

    // Updates item numbers and controls remove button visibility
    function updateItemNumbersAndVisibility() {
        const items = packageItemsContainer.querySelectorAll('.card.package-item-template');
        items.forEach((item, index) => {
            const itemNumberSpan = item.querySelector('.item-number');
            if (itemNumberSpan) {
                itemNumberSpan.textContent = index + 1;
            }

            const removeButton = item.querySelector('.remove-item');
            if (removeButton) {
                // Hide remove button for the first item (index 0), show for others
                removeButton.style.display = (index === 0) ? 'none' : 'block';
            }
        });
    }

    // Attach event listener for 'Add Another Package' button
    addPackageItemButton.addEventListener('click', function() {
        createAndAppendPackageItem(null);
    });

    // Handle initial page load or re-population from old input
    if (oldBookingItems.length > 0) {
        // Remove initial template, rebuild from old data
        const initialTemplateInDom = packageItemsContainer.querySelector('.package-item-template');
        if (initialTemplateInDom) {
            initialTemplateInDom.remove();
        }

        oldBookingItems.forEach((itemData, index) => {
            nextItemIndex = index - 1; // Adjust index as it increments in createAndAppendPackageItem
            createAndAppendPackageItem(itemData);
        });

    } else {
        // Ensure initial item is correctly indexed and pre-selected if applicable
        const initialItem = packageItemsContainer.querySelector('.package-item-template');
        if (initialItem) {
             initialItem.querySelectorAll('select, input, textarea').forEach(input => {
                const oldName = input.name;
                if (oldName) {
                    const fieldNameMatch = oldName.match(/items\[\d+\]\[(.*)\]/);
                    const fieldName = fieldNameMatch ? fieldNameMatch[1] : '';
                    input.name = `items[0][${fieldName}]`;
                }
             });

            if (selectedInitialPackageId) {
                const packageSelect = initialItem.querySelector('.package-select');
                if (packageSelect) {
                    packageSelect.value = selectedInitialPackageId;
                }
            }
        }
        nextItemIndex = 0; // Set index for subsequent additions
    }

    // Initial call to set correct item numbers and button visibility
    updateItemNumbersAndVisibility();
});
