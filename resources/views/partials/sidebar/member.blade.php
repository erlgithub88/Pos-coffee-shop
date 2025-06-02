
<li class="nav-item">
	<?php
$pengajuanRoutes = ['submission.index', 'submission.create', 'submission.edit'];
$isPengajuanActive = in_array(Route::currentRouteName(), $pengajuanRoutes);
    ?>
	<a href="{{ route('submission.index') }}" class="{{ $isPengajuanActive ? 'active' : '' }} nav-link">
		<i class="nav-icon fas fa-file-alt"></i>
		<p>
			Pengajuan
		</p>
	</a>
</li>