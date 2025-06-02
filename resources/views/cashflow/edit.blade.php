@extends('layouts.admin-layout')

@section('title', 'Edit Cashflow')

@section('content')
@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

<style>
	body { font-family: 'Inter', sans-serif; }
	.card-primary {
		border-radius: 0.5rem;
		box-shadow: 0 0 15px rgba(59, 130, 246, 0.3);
		border: none;
	}
	.card-header {
		background-color: #3b82f6;
		color: #fff;
		font-weight: 600;
		font-size: 1.25rem;
		border-radius: 0.5rem 0.5rem 0 0;
	}
	.btn-primary {
		background-color: #2563eb;
		border-color: #2563eb;
		font-weight: 600;
		padding: 0.5rem 1.5rem;
		font-size: 1rem;
		transition: background-color 0.3s ease;
	}
	.btn-primary:hover { background-color: #1d4ed8; }
	.btn-cancel {
		background-color: #6b7280;
		color: #fff;
		font-weight: 600;
		padding: 0.5rem 1.5rem;
		font-size: 1rem;
		border: none;
		border-radius: 0.375rem;
		margin-left: 0.5rem;
		transition: background-color 0.3s ease;
		cursor: pointer;
	}
	.btn-cancel:hover { background-color: #4b5563; }
	.input-group-text {
		background-color: #f1f5f9;
		font-weight: 600;
	}
</style>
@endpush

<div class="card card-primary shadow-sm">
	<div class="card-header">
		<h3 class="card-title"><i class="fas fa-edit mr-2"></i> Edit Cashflow</h3>
	</div>
	<form id="editCashflowForm" action="{{ route('cashflow.update', $cashflow->id) }}" method="post" novalidate>
		@csrf
		@method('put')
		<div class="card-body">
			<div class="row">
				<div class="col-sm-6">
					<label for="title" class="font-weight-semibold">
						<i class="fas fa-heading mr-1 text-primary"></i> Title
					</label>
					<div class="input-group mb-3">
						<span class="input-group-text"><i class="fas fa-heading text-primary"></i></span>
						<input type="text" name="title" value="{{ old('title', $cashflow->title) }}"
							class="form-control @error('title') is-invalid @enderror" placeholder="Enter title" required>
					</div>
					@error('title')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>

				<div class="col-sm-6">
					<label for="nominal" class="font-weight-semibold">
						<i class="fas fa-money-bill mr-1 text-success"></i> Nominal
					</label>
					<div class="input-group mb-3">
						<span class="input-group-text"><i class="fas fa-money-bill text-success"></i></span>
						<input type="number" name="nominal" value="{{ old('nominal', $cashflow->nominal) }}"
							class="form-control @error('nominal') is-invalid @enderror" placeholder="Enter nominal" required>
					</div>
					@error('nominal')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
			</div>

			<label for="desc" class="font-weight-semibold">
				<i class="fas fa-align-left mr-1 text-info"></i> Description
			</label>
			<div class="input-group mb-3">
				<span class="input-group-text"><i class="fas fa-align-left text-info"></i></span>
				<textarea class="form-control @error('desc') is-invalid @enderror" name="desc" rows="3"
					placeholder="Enter description" required>{{ old('desc', $cashflow->desc) }}</textarea>
			</div>
			@error('desc')
				<p class="text-danger">{{ $message }}</p>
			@enderror

			<div class="row">
				<div class="col-sm-6">
					<label for="type" class="font-weight-semibold">
						<i class="fas fa-random mr-1 text-warning"></i> Type
					</label>
					<div class="input-group mb-3">
						<span class="input-group-text"><i class="fas fa-random text-warning"></i></span>
						<select name="type" class="form-control @error('type') is-invalid @enderror" required>
							<option value="income" {{ old('type', $cashflow->type) == 'income' ? 'selected' : '' }}>Income</option>
							<option value="expense" {{ old('type', $cashflow->type) == 'expense' ? 'selected' : '' }}>Expense</option>
						</select>
					</div>
					@error('type')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>

				<div class="col-sm-6">
					<label for="date" class="font-weight-semibold">
						<i class="fas fa-calendar-alt mr-1 text-danger"></i> Date (YYYY-MM-DD)
					</label>
					<div class="input-group mb-3">
						<span class="input-group-text"><i class="fas fa-calendar-alt text-danger"></i></span>
						<input type="date" name="date" value="{{ old('date', date('Y-m-d', strtotime($cashflow->date))) }}"
							class="form-control @error('date') is-invalid @enderror" required>
					</div>
					@error('date')
						<p class="text-danger">{{ $message }}</p>
					@enderror
				</div>
			</div>
		</div>

		<div class="card-footer d-flex justify-content-end">
			<button type="submit" class="btn btn-primary">
				<i class="fas fa-save mr-1"></i> Submit
			</button>
			<a href="{{ route('cashflow.index') }}" class="btn btn-cancel">
				<i class="fas fa-times mr-1"></i> Batal
			</a>
		</div>
	</form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
<script>
	document.getElementById('editCashflowForm').addEventListener('submit', function(e) {
		e.preventDefault();

		const form = this;
		const title = form.title.value.trim();
		const nominal = form.nominal.value.trim();
		const desc = form.desc.value.trim();
		const type = form.type.value.trim();
		const date = form.date.value.trim();

		if (!title || !nominal || !desc || !type || !date) {
			Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Semua field wajib diisi!',
				timer: 2500,
				timerProgressBar: true,
				showConfirmButton: false,
			});
			return;
		}

		Swal.fire({
			title: 'Yakin ingin menyimpan perubahan?',
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#2563eb',
			cancelButtonColor: '#6b7280',
			confirmButtonText: 'Ya, simpan!',
			cancelButtonText: 'Batal'
		}).then((result) => {
			if (result.isConfirmed) {
				form.submit();
			}
		});
	});
</script>
@endpush
@endsection
