@extends('layouts.app')

@section('title', 'Edit Contact - Contact Manager')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bi bi-pencil me-2"></i>
                    Edit Contact: {{ $contact->name }}
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('contacts.update', $contact) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Name Field -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">
                                <i class="bi bi-person me-1"></i>
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $contact->name) }}" 
                                   required 
                                   autofocus
                                   placeholder="Enter full name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone Field -->
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">
                                <i class="bi bi-telephone me-1"></i>
                                Phone Number <span class="text-danger">*</span>
                            </label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $contact->phone) }}" 
                                   required
                                   placeholder="e.g., +90 333 1234567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>
                                Email Address
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $contact->email) }}"
                                   placeholder="example@domain.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Company Field -->
                        <div class="col-md-6 mb-3">
                            <label for="company" class="form-label">
                                <i class="bi bi-building me-1"></i>
                                Company/Organization
                            </label>
                            <input type="text" 
                                   class="form-control @error('company') is-invalid @enderror" 
                                   id="company" 
                                   name="company" 
                                   value="{{ old('company', $contact->company) }}"
                                   placeholder="Company name">
                            @error('company')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address Field -->
                        <div class="col-12 mb-3">
                            <label for="address" class="form-label">
                                <i class="bi bi-geo-alt me-1"></i>
                                Address
                            </label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3"
                                      placeholder="Full address including city, state, postal code">{{ old('address', $contact->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Notes Field -->
                        <div class="col-12 mb-3">
                            <label for="notes" class="form-label">
                                <i class="bi bi-journal-text me-1"></i>
                                Notes
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3"
                                      placeholder="Additional notes about this contact">{{ old('notes', $contact->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex gap-2 pt-3 border-top">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg me-1"></i>
                            Update Contact
                        </button>
                        <a href="{{ route('contacts.show', $contact) }}" class="btn btn-outline-info">
                            <i class="bi bi-eye me-1"></i>
                            View Contact
                        </a>
                        <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Contacts
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact History Card -->
        <div class="card mt-4">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="bi bi-clock-history me-2"></i>
                    Contact Information
                </h6>
                <div class="row text-sm">
                    <div class="col-md-6">
                        <small class="text-muted">
                            <strong>Created:</strong> {{ $contact->created_at->format('F j, Y \a\t g:i A') }}
                        </small>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">
                            <strong>Last Updated:</strong> {{ $contact->updated_at->format('F j, Y \a\t g:i A') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Phone number formatting
    const phoneInput = document.getElementById('phone');
    
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        
        // Basic formatting for Turkish numbers
        if (value.startsWith('90')) {
            if (value.length > 2) {
                value = '+90 ' + value.substring(2);
            }
            if (value.length > 7) {
                value = value.substring(0, 7) + ' ' + value.substring(7);
            }
            if (value.length > 11) {
                value = value.substring(0, 11) + ' ' + value.substring(11);
            }
        }
        
        e.target.value = value;
    });

    // Form validation feedback
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const nameInput = document.getElementById('name');
        const phoneInput = document.getElementById('phone');
        
        if (!nameInput.value.trim()) {
            e.preventDefault();
            nameInput.classList.add('is-invalid');
            nameInput.focus();
            return;
        }
        
        if (!phoneInput.value.trim()) {
            e.preventDefault();
            phoneInput.classList.add('is-invalid');
            phoneInput.focus();
            return;
        }
    });

    // Remove invalid class on input
    document.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
    });

    // Unsaved changes warning
    let formChanged = false;
    const originalData = new FormData(form);
    
    form.addEventListener('input', function() {
        formChanged = true;
    });

    window.addEventListener('beforeunload', function(e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });

    form.addEventListener('submit', function() {
        formChanged = false;
    });
});
</script>
@endpush
