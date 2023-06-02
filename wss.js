document.addEventListener('DOMContentLoaded', function() {
    var popup = document.querySelector('.wss-popup');
    var overlay = document.querySelector('.wss-overlay');
    var closeBtn = document.querySelector('.wss-close');
    var storeSelect = document.querySelector('.wss-store-select');
    var addToCartBtn = document.querySelector('.wss-add-to-cart');
    var variationSelects = document.querySelectorAll('.variations select');

    function openPopup() {
        popup.style.display = 'block';
        overlay.style.display = 'block';
    }

    function closePopup() {
        popup.style.display = 'none';
        overlay.style.display = 'none';
        document.body.style.overflow = 'auto'; // Allow scrolling and interaction
    }

    function getSelectedVariations() {
        var variations = {};

        variationSelects.forEach(function(select) {
            var attribute = select.getAttribute('name');
            var value = select.value;
            variations[attribute] = value;
        });

        return variations;
    }

    function redirectToStore() {
        var selectedStoreUrl = storeSelect.value;
        if (selectedStoreUrl !== '') {
            var variations = getSelectedVariations();

            var queryString = Object.keys(variations).map(function(attribute) {
                var value = variations[attribute];
                return encodeURIComponent(attribute) + '=' + encodeURIComponent(value);
            }).join('&');

            selectedStoreUrl += (selectedStoreUrl.includes('?') ? '&' : '?') + queryString;

            window.open(selectedStoreUrl, '_blank');
        }

        closePopup();
    }

    openPopup();

    closeBtn.addEventListener('click', function() {
        closePopup();
    });

    overlay.addEventListener('click', function() {
        closePopup();
    });

    addToCartBtn.addEventListener('click', function() {
        redirectToStore();
    });
});
