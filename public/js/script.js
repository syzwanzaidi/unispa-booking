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
        newItem.removeAttribute('style');
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
                input.removeAttribute('disabled');
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
document.addEventListener('turbo:render', function() {
    initializeSidebarPersistence();
});


function initializeSidebarPersistence() {
    var sidebar = document.getElementById('sidebar');

    if (sidebar) {
        let bsOffcanvas = bootstrap.Offcanvas.getInstance(sidebar);
        if (!bsOffcanvas) {
            bsOffcanvas = new bootstrap.Offcanvas(sidebar, {
                backdrop: true
            });
        }
        var sidebarToggleButton = document.querySelector('[data-bs-target="#sidebar"]');
        if (sidebarToggleButton) {
            if (!sidebarToggleButton._sidebarToggleListenerAdded) {
                 sidebarToggleButton.addEventListener('click', function() {
                    const offcanvasInstance = bootstrap.Offcanvas.getInstance(sidebar);
                    if (offcanvasInstance && offcanvasInstance._isShown) {
                        localStorage.setItem('sidebarState', 'closed');
                    } else {
                        localStorage.setItem('sidebarState', 'open');
                    }
                });
                sidebarToggleButton._sidebarToggleListenerAdded = true;
            }
        }
        if (localStorage.getItem('sidebarState') === 'open') {
            if (!bsOffcanvas._isShown) {
                bsOffcanvas.show();
            }
        } else {
            if (bsOffcanvas._isShown) {
                 bsOffcanvas.hide();
            }
        }
        if (!sidebar._sidebarHideListenerAdded) {
            sidebar.addEventListener('hide.bs.offcanvas', function () {
                localStorage.setItem('sidebarState', 'closed');
            });
            sidebar._sidebarHideListenerAdded = true;
        }
        if (!sidebar._sidebarShowListenerAdded) {
            sidebar.addEventListener('show.bs.offcanvas', function () {
                localStorage.setItem('sidebarState', 'open');
            });
            sidebar._sidebarShowListenerAdded = true;
        }
    }
}
  document.addEventListener('DOMContentLoaded', function () {
        const packageItemMasterTemplate = document.getElementById('packageItemMasterTemplate');
        const packageItemsContainer = document.getElementById('packageItemsContainer');
        const addPackageItemButton = document.getElementById('addPackageItem');

        const totalBeforeDiscountDisplay = document.getElementById('totalBeforeDiscountDisplay');
        const discountAmountDisplay = document.getElementById('discountAmountDisplay');
        const totalAfterDiscountDisplay = document.getElementById('totalAfterDiscountDisplay');

        let itemCounter = 0;
        function createPackageItem(initialData = null) {
            const newItem = packageItemMasterTemplate.cloneNode(true);
            newItem.style.display = 'block';
            newItem.id = `packageItem_${itemCounter}`;
            newItem.querySelector('.item-number').textContent = itemCounter + 1;
            newItem.querySelectorAll('[name*="items[0]"]').forEach(input => {
                input.name = input.name.replace('items[0]', `items[${itemCounter}]`);
                input.id = input.id ? input.id.replace('_0', `_${itemCounter}`) : '';
                input.disabled = false;
            });
            if (initialData) {
                if (initialData.package_id) newItem.querySelector('.package-select').value = initialData.package_id;
                if (initialData.item_pax) newItem.querySelector('.pax-input').value = initialData.item_pax;
                if (initialData.item_start_time) newItem.querySelector('.time-slot-select').value = initialData.item_start_time;
                if (initialData.for_whom_name) newItem.querySelector('.for-whom-input').value = initialData.for_whom_name;
            } else if (window.selectedInitialPackageId && itemCounter === 0) {
                newItem.querySelector('.package-select').value = window.selectedInitialPackageId;
            }
            newItem.querySelector('.package-select').addEventListener('change', calculateAndDisplayTotals);
            newItem.querySelector('.pax-input').addEventListener('input', calculateAndDisplayTotals);
            newItem.querySelector('.remove-item').addEventListener('click', function() {
                newItem.remove();
                reindexItems();
                calculateAndDisplayTotals();
            });

            packageItemsContainer.appendChild(newItem);
            itemCounter++;
        }
        function reindexItems() {
            let currentItemIndex = 0;
            packageItemsContainer.querySelectorAll('.package-item-template').forEach((itemCard, index) => {
                itemCard.id = `packageItem_${currentItemIndex}`;
                itemCard.querySelector('.item-number').textContent = currentItemIndex + 1;
                itemCard.querySelectorAll('[name*="items["]').forEach(input => {
                    input.name = input.name.replace(/items\[\d+\]/, `items[${currentItemIndex}]`);
                });
                currentItemIndex++;
            });
            itemCounter = currentItemIndex;
        }


        function calculateAndDisplayTotals() {
            let totalBeforeDiscount = 0;

            packageItemsContainer.querySelectorAll('.package-item-template').forEach(itemCard => {
                const packageSelect = itemCard.querySelector('.package-select');
                const paxInput = itemCard.querySelector('.pax-input');

                const packageId = packageSelect.value;
                const pax = parseInt(paxInput.value) || 0;

                if (packageId && window.allPackages[packageId]) {
                    const packagePrice = parseFloat(window.allPackages[packageId].package_price);
                    totalBeforeDiscount += (packagePrice * pax);
                }
            });

            let discountAmount = 0;
            if (window.isMember) {
                discountAmount = totalBeforeDiscount * 0.10;
            }

            const totalAfterDiscount = totalBeforeDiscount - discountAmount;

            totalBeforeDiscountDisplay.textContent = totalBeforeDiscount.toFixed(2);
            discountAmountDisplay.textContent = discountAmount.toFixed(2);
            totalAfterDiscountDisplay.textContent = totalAfterDiscount.toFixed(2);
        }
        if (window.oldBookingItems.length > 0) {
            window.oldBookingItems.forEach(item => createPackageItem(item));
        } else {
            createPackageItem();
        }
        addPackageItemButton.addEventListener('click', function () {
            createPackageItem();
            calculateAndDisplayTotals();
        });
        calculateAndDisplayTotals();
    });
