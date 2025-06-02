
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
<li class="nav-item">
	<?php
$memberRoutes = ['member.index', 'member.create', 'member.edit'];
$isMemberActive = in_array(Route::currentRouteName(), $memberRoutes);
    ?>
	<a href="{{ route('member.index') }}" class="{{ $isMemberActive ? 'active' : '' }} nav-link">
		<i class="nav-icon fas fa-id-card"></i>
		<p>
			Member
		</p>
	</a>
</li>
<li class="nav-item">
	<?php
$submissionRoutes = ['submission.index', 'submission.create', 'submission.edit'];
$isSubmissionActive = in_array(Route::currentRouteName(), $submissionRoutes);
    ?>
	<a href="{{ route('submission.index') }}" class="{{ $isSubmissionActive ? 'active' : '' }} nav-link">
		<i class="nav-icon fas fa-file-alt"></i>
		<p>
			Submission
		</p>
	</a>
</li>