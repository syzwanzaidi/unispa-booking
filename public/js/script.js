document.addEventListener('DOMContentLoaded', function () {
    const packageItemsContainer = document.getElementById('packageItemsContainer');
    const addPackageItemButton = document.getElementById('addPackageItem');

    const oldBookingItems = window.oldBookingItems || [];
    const selectedInitialPackageId = window.selectedInitialPackageId;

    let nextItemIndex = -1;

    function createAndAppendPackageItem(initialData = null) {
        nextItemIndex++;

        const masterTemplateContainer = document.getElementById('packageItemMasterTemplate');
        if (!masterTemplateContainer) {
            console.error("Error: '#packageItemMasterTemplate' not found.");
            return;
        }
        const originalTemplate = masterTemplateContainer.querySelector('.package-item-template');
        if (!originalTemplate) {
            console.error("Error: '.package-item-template' not found within '#packageItemMasterTemplate'.");
            return;
        }

        const newItem = originalTemplate.cloneNode(true);
        newItem.removeAttribute('style'); // Ensures the cloned item is visible

        // Update input names, set values, and REMOVE disabled attribute
        newItem.querySelectorAll('select, input, textarea').forEach(input => {
            const fieldName = input.name.match(/items\[\d+\]\[(.*)\]/)?.[1];
            if (fieldName) {
                input.name = `items[${nextItemIndex}][${fieldName}]`;
                input.value = (initialData && initialData[fieldName] !== undefined)
                              ? initialData[fieldName]
                              : ((input.type === 'number' && fieldName === 'item_pax') ? 1 : '');
                if (input.tagName === 'SELECT' && input.querySelector('option[value=""]')) {
                    input.value = '';
                }
                input.removeAttribute('disabled'); // <--- ADD THIS LINE: Make the input active
            }
        });

        const removeButton = newItem.querySelector('.remove-item');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                newItem.remove();
                updateItemNumbersAndVisibility();
            });
        }

        if (nextItemIndex === 0 && initialData === null && selectedInitialPackageId) {
            const packageSelect = newItem.querySelector('.package-select');
            if (packageSelect) {
                packageSelect.value = selectedInitialPackageId;
            }
        }

        packageItemsContainer.appendChild(newItem);
        updateItemNumbersAndVisibility();
    }

    function updateItemNumbersAndVisibility() {
        const items = packageItemsContainer.querySelectorAll('.card.package-item-template');
        items.forEach((item, index) => {
            const itemNumberSpan = item.querySelector('.item-number');
            if (itemNumberSpan) {
                itemNumberSpan.textContent = index + 1;
            }

            const removeButton = item.querySelector('.remove-item');
            if (removeButton) {
                removeButton.style.display = (index === 0) ? 'none' : 'block';
            }
        });
    }

    addPackageItemButton.addEventListener('click', function() {
        createAndAppendPackageItem(null);
    });

    // --- Initial form rendering logic on page load ---
    packageItemsContainer.innerHTML = '';
    nextItemIndex = -1;

    if (oldBookingItems.length > 0) {
        oldBookingItems.forEach((itemData) => {
            createAndAppendPackageItem(itemData);
        });
    } else {
        createAndAppendPackageItem(null);
    }
});
