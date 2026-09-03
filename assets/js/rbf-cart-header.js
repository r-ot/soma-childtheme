(function() {

	'use strict';

	function initCartHeader() {

		var countElement = document.querySelector('[data-rbf-cart-count-value]');
		var labelElement = document.querySelector('[data-rbf-cart-count-product-label]');

		if (
			!countElement
			|| !labelElement
			|| !window.wp
			|| !window.wp.data
			|| !window.wc
			|| !window.wc.wcBlocksData
			|| !window.wc.wcBlocksData.cartStore
		) {
			return;
		}

		var cartStore = window.wc.wcBlocksData.cartStore;
		var previousCount = null;

		function updateCartCount() {

			var cartData = window.wp.data.select(cartStore).getCartData();

			if (
				!cartData
				|| typeof cartData.itemsCount === 'undefined'
			) {
				return;
			}

			var itemCount = parseInt(cartData.itemsCount, 10);

			if (itemCount === previousCount) {
				return;
			}

			previousCount = itemCount;

			countElement.textContent = itemCount;
			labelElement.textContent = itemCount === 1
				? 'Produkt'
				: 'Produkte';
		}

		updateCartCount();

		window.wp.data.subscribe(
			function() {
				updateCartCount();
			},
			cartStore
		);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initCartHeader);
	} else {
		initCartHeader();
	}

})();