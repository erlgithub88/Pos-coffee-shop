
<li class="nav-item">
	<?php
$orderRoutes = ['order.index', 'order.create', 'order.detail'];
$isOrderActive = in_array(Route::currentRouteName(), $orderRoutes);
										?>
	<a href="{{ route('order.index') }}" class="{{ $isOrderActive ? 'active' : '' }} nav-link">
		<i class="nav-icon fas fa-shopping-cart"></i>
		<p>
			Order
		</p>
	</a>
</li>
<li class="nav-item">
	<?php
$paymentMethodRoutes = ['payment-method.index', 'payment-method.create', 'payment-method.edit'];
$isPaymentMethodActive = in_array(Route::currentRouteName(), $paymentMethodRoutes);
										?>
	<a href="{{ route('payment-method.index') }}" class="{{ $isPaymentMethodActive ? 'active' : '' }} nav-link">
		<i class="nav-icon fas fa-credit-card"></i>
		<p>
			Payment Method
		</p>
	</a>
</li>
