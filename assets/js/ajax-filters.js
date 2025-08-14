document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.querySelector('.filters-form'); // I will need to add this class to the form
    if (!filterForm) {
        return;
    }

    const productGrid = document.querySelector('.product-grid-container'); // I will need to add this class to the grid container
    if (!productGrid) {
        return;
    }

    function fetchProducts() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams(formData);
        const url = `${ajax_object.ajax_url}?action=filter_products&${params.toString()}`;

        // Add loading state
        productGrid.style.opacity = '0.5';

        fetch(url, {
            method: 'POST', // Using POST to send data
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            productGrid.innerHTML = data;
            productGrid.style.opacity = '1';
            // Update browser URL
            window.history.pushState({}, '', `${ajax_object.archive_url}?${params.toString()}`);
        })
        .catch(error => {
            console.error('Error:', error);
            productGrid.style.opacity = '1';
        });
    }

    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        fetchProducts();
    });

    const selects = filterForm.querySelectorAll('select');
    selects.forEach(select => {
        select.addEventListener('change', fetchProducts);
    });
});
