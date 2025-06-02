@extends('layouts.admin-layout')

@section('title', 'Create User')

@section('content')
	<div class="card card-primary">
		<div class="card-header">
			<h3 class="card-title">Create New User</h3>
		</div>
		<form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
			@csrf
			<div class="card-body">
				<div class="form-group">
					<label for="email">Email</label>
					<input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email"
						placeholder="Enter email" required>
					@error('email')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
						id="password" placeholder="Enter password" required>
					@error('password')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="role">Role</label>
					<select name="role" class="form-control @error('role') is-invalid @enderror" id="role" required>
						<option value="owner">Owner</option>
						<option value="manager">Manager</option>
						<option value="cashier">Cashier</option>
						<option value="barista">Barista</option>
					</select>
					@error('role')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="name">Name</label>
					<input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name"
						placeholder="Enter name" required>
					@error('name')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="phone_number">Phone Number</label>
					<input type="text" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
						id="phone_number" placeholder="Enter phone number">
					@error('phone_number')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="address">Address</label>
					<input type="text" name="address" class="form-control @error('address') is-invalid @enderror" id="address"
						placeholder="Enter address">
					@error('address')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
				<div class="form-group">
					<label for="monthly_wage">Monthly Wage</label>
					<input type="number" name="monthly_wage" class="form-control @error('monthly_wage') is-invalid @enderror"
						id="monthly_wage" placeholder="Enter monthly wage">
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
				<div id="updatedImagePreview" style="display: none;">
					<p>Image Preview</p>
					<img id="previewImage" src="#" alt="Preview Image" style="max-width: 200px; max-height: 200px;">
				</div>
			</div>
			<div class="card-footer">
				<button type="submit" class="btn btn-primary">Submit</button>
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
					}

					reader.readAsDataURL(this.files[0]);
				});
			});
		</script>
	@endpush
@endsection