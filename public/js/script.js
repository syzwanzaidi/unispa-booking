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
document.addEventListener('turbo:load', function() {
    initializeSidebarPersistence();
});

// If using Turbo Streams or other dynamic content updates that might include new sidebar elements,
// you might also listen to turbo:frame-load or turbo:render
document.addEventListener('turbo:render', function() { // Added turbo:render listener
    initializeSidebarPersistence();
});


function initializeSidebarPersistence() {
    var sidebar = document.getElementById('sidebar');

    if (sidebar) {
        // Ensure offcanvas instance is created/recreated
        let bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebar);
        if (!bsOffcanvas) {
            bsOffcanvas = new bootstrap.Offcanvas(sidebar, {
                backdrop: true // Ensure backdrop is enabled for proper toggling
            });
        }

        // Add event listener to the toggle button (ensure it's only added once)
        var sidebarToggleButton = document.querySelector('[data-bs-target="#sidebar"]');
        if (sidebarToggleButton) {
            // Remove previous listener to prevent duplicates if initializeSidebarPersistence runs multiple times
            if (!sidebarToggleButton._sidebarToggleListenerAdded) { // Custom flag to prevent duplicates
                 sidebarToggleButton.addEventListener('click', function() {
                    const offcanvasInstance = bootstrap.Offcanvas.getInstance(sidebar);
                    // Toggle state
                    if (offcanvasInstance && offcanvasInstance._isShown) {
                        localStorage.setItem('sidebarState', 'closed');
                    } else {
                        localStorage.setItem('sidebarState', 'open');
                    }
                    // No need to manually show/hide here, Bootstrap's data-bs-toggle handles it
                    // The listeners below will catch the actual show/hide events
                });
                sidebarToggleButton._sidebarToggleListenerAdded = true; // Mark as added
            }
        }

        // On page load (or Turbo navigation), check localStorage and open/close sidebar
        if (localStorage.getItem('sidebarState') === 'open') {
            // Only show if it's not already shown to prevent re-opening animation
            if (!bsOffcanvas._isShown) {
                bsOffcanvas.show();
            }
        } else {
            // Ensure it starts hidden if state is 'closed' or not set, and hide if it's currently open
            if (bsOffcanvas._isShown) {
                 bsOffcanvas.hide();
            }
        }

        // Listen for the 'hide' event (when user clicks backdrop or close button)
        // Ensure this listener is also only added once
        if (!sidebar._sidebarHideListenerAdded) {
            sidebar.addEventListener('hide.bs.offcanvas', function () {
                localStorage.setItem('sidebarState', 'closed');
            });
            sidebar._sidebarHideListenerAdded = true;
        }

        // Listen for the 'show' event (when sidebar opens)
        if (!sidebar._sidebarShowListenerAdded) {
            sidebar.addEventListener('show.bs.offcanvas', function () {
                localStorage.setItem('sidebarState', 'open');
            });
            sidebar._sidebarShowListenerAdded = true;
        }
    }
}
