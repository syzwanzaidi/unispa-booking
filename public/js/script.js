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
            if (input.id) {
                input.id = input.id.replace('_0', `_${itemCounter}`);
            }
            input.disabled = false;

            if (!initialData) {
                if (input.tagName === 'SELECT') {
                    input.value = '';
                } else if (input.type === 'number' && input.name.includes('[item_pax]')) {
                    input.value = 1;
                } else if (input.type === 'time') {
                    input.value = '';
                } else {
                    input.value = '';
                }
            }
        });

        if (initialData) {
            if (initialData.package_id) newItem.querySelector('.package-select').value = initialData.package_id;
            if (initialData.item_pax) newItem.querySelector('.pax-input').value = initialData.item_pax;
            if (initialData.item_start_time) newItem.querySelector('.time-slot-select').value = initialData.item_start_time;
            if (initialData.for_whom_name) newItem.querySelector('.for-whom-input').value = initialData.for_whom_name;
        } else if (window.selectedInitialPackageId && itemCounter === 0) {
            const packageSelect = newItem.querySelector('.package-select');
            if (packageSelect) {
                packageSelect.value = window.selectedInitialPackageId;
            }
        }

        newItem.querySelector('.package-select').addEventListener('change', calculateAndDisplayTotals);
        newItem.querySelector('.pax-input').addEventListener('input', calculateAndDisplayTotals);
        
        const removeButton = newItem.querySelector('.remove-item');
        if (removeButton) {
            removeButton.addEventListener('click', function() {
                newItem.remove();
                reindexItems();
                calculateAndDisplayTotals();
            });
            
            if (itemCounter === 0) {
                removeButton.style.display = 'none';
            } else {
                removeButton.style.display = 'block';
            }
        }
        
        packageItemsContainer.appendChild(newItem);
        itemCounter++;
    }

    function reindexItems() {
        let currentItemIndex = 0;
        const items = packageItemsContainer.querySelectorAll('.package-item-template');

        items.forEach((itemCard, index) => {
            itemCard.id = `packageItem_${currentItemIndex}`;
            itemCard.querySelector('.item-number').textContent = currentItemIndex + 1;
            
            itemCard.querySelectorAll('[name*="items["]').forEach(input => {
                const fieldName = input.name.match(/items\[\d+\]\[(.*)\]/)?.[1];
                if (fieldName) {
                    input.name = `items[${currentItemIndex}][${fieldName}]`;
                    if (input.id) {
                        input.id = input.id.replace(/_\d+$/, `_${currentItemIndex}`);
                    }
                }
            });

            const removeButton = itemCard.querySelector('.remove-item');
            if (removeButton) {
                if (index === 0) {
                    removeButton.style.display = 'none';
                } else {
                    removeButton.style.display = 'block';
                }
            }

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

            if (packageId && window.allPackages && window.allPackages[packageId]) {
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

    if (window.oldBookingItems && window.oldBookingItems.length > 0) {
        window.oldBookingItems.forEach(item => createPackageItem(item));
    } else {
        createPackageItem();
    }

    addPackageItemButton.addEventListener('click', function () {
        createPackageItem();
        calculateAndDisplayTotals();
        reindexItems();
    });

    calculateAndDisplayTotals();
    reindexItems();
});