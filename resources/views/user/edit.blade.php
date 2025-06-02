@extends('layouts.admin-layout')

@section('title', 'Edit User')

@section('content')
	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title">Edit User</h3>
		</div>
		<form action="{{ route('user.update', ['user' => $user->id]) }}" method="post" enctype="multipart/form-data">
			@csrf
			@method('PUT')
			<div class="card-body">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
						value="{{ old('email', $user->email) }}" placeholder="Enter email" required>
					@error('email')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
						id="password" placeholder="Leave blank to keep current password">
					@error('password')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="role">Role</label>
					<select name="role" class="form-control @error('role') is-invalid @enderror" id="role" required>
						<option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>Owner</option>
						<option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
						<option value="cashier" {{ $user->role === 'cashier' ? 'selected' : '' }}>Cashier</option>
						<option value="barista" {{ $user->role === 'barista' ? 'selected' : '' }}>Barista</option>
					</select>
					@error('role')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
						value="{{ old('name', $user->owner->name ?? $user->manager->name ?? $user->cashier->name ?? $user->barista->name ?? '') }}"
						placeholder="Enter name" required>
					@error('name')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="phone_number">Phone Number</label>
					<input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
						id="phone_number"
						value="{{ old('phone_number', $user->owner->phone_number ?? $user->manager->phone_number ?? $user->cashier->phone_number ?? $user->barista->phone_number ?? '') }}"
						placeholder="Enter phone number">
					@error('phone_number')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="address">Address</label>
					<input type="text" name="address" class="form-control @error('address') is-invalid @enderror" id="address"
						value="{{ old('address', $user->owner->address ?? $user->manager->address ?? $user->cashier->address ?? $user->barista->address ?? '') }}"
						placeholder="Enter address">
					@error('address')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="monthly_wage">Monthly Wage</label>
					<input type="number" name="monthly_wage" class="form-control @error('monthly_wage') is-invalid @enderror"
						id="monthly_wage"
						value="{{ old('monthly_wage', $user->owner->monthly_wage ?? $user->manager->monthly_wage ?? $user->cashier->monthly_wage ?? $user->barista->monthly_wage ?? '') }}"
						placeholder="Enter monthly wage">
					@error('monthly_wage')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="image">Image</label>
					<div class="input-group">
						<div class="custom-file">
							<input type="file" name="image" class="custom-file-input @error('image') is-invalid @enderror" id="image">
							<label class="custom-file-label" for="image">Choose file</label>
						</div>
						<div class="input-group-append">
							<span class="input-group-text">Upload</span>
						</div>
					</div>
					@error('image')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				@if ($user->image_path)
					<div id="currentImagePreview">
						<p>Current Image</p>
						<img src="{{ asset('storage/' . $user->image_path) }}" alt="Current Image"
							style="max-width: 200px; max-height: 200px;">
					</div>
				@endif
				<div id="updatedImagePreview" style="display: none;">
					<p>Image Preview</p>
					<img id="previewImage" src="#" alt="Preview Image" style="max-width: 200px; max-height: 200px;">
				</div>
			</div>
			<div class="card-footer">
				<button type="submit" class="btn btn-primary">Update</button>
			</div>
		</form>
	</div>

	@push('scripts')
		<script src="{{ asset('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
		<script>
			$(function () {
				bsCustomFileInput.init();

				$('#image').change(function () {
					let reader = new FileReader();

					reader.onload = (e) => {
						$('#previewImage').attr('src', e.target.result);
						$('#updatedImagePreview').show();
						$('#currentImagePreview').hide();
					}

					reader.readAsDataURL(this.files[0]);
				});
			});
		</script>
	@endpush
@endsection