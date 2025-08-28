@extends('layouts.app')

@section('title', $contact->name . ' - Contact Manager')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <!-- Contact Header -->
        <div class="card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex align-items-center">
                            <div class="contact-avatar me-4" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ strtoupper(substr($contact->name, 0, 1)) }}
                            </div>
                            <div>
                                <h2 class="mb-1">{{ $contact->name }}</h2>
                                @if($contact->company)
                                    <p class="text-muted mb-2">
                                        <i class="bi bi-building me-1"></i>
                                        {{ $contact->company }}
                                    </p>
                                @endif
                                <div class="d-flex gap-3">
                                    <a href="tel:{{ $contact->phone }}" class="text-decoration-none">
                                        <i class="bi bi-telephone text-success me-1"></i>
                                        {{ $contact->phone }}
                                    </a>
                                    @if($contact->email)
                                        <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                            <i class="bi bi-envelope text-primary me-1"></i>
                                            {{ $contact->email }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="btn-group">
                            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-primary">
                                <i class="bi bi-pencil me-1"></i>
                                Edit
                            </a>
                            <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="tel:{{ $contact->phone }}">
                                        <i class="bi bi-telephone me-2"></i>
                                        Call Contact
                                    </a>
                                </li>
                                @if($contact->email)
                                    <li>
                                        <a class="dropdown-item" href="mailto:{{ $contact->email }}">
                                            <i class="bi bi-envelope me-2"></i>
                                            Send Email
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger delete-btn">
                                            <i class="bi bi-trash me-2"></i>
                                            Delete Contact
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="row mt-4">
            <!-- Contact Information -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>
                            Contact Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Full Name</label>
                                <p class="mb-0">{{ $contact->name }}</p>
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <p class="mb-0">
                                    <a href="tel:{{ $contact->phone }}" class="text-decoration-none">
                                        <i class="bi bi-telephone me-1"></i>
                                        {{ $contact->phone }}
                                    </a>
                                </p>
                            </div>
                            
                            @if($contact->email)
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <p class="mb-0">
                                        <a href="mailto:{{ $contact->email }}" class="text-decoration-none">
                                            <i class="bi bi-envelope me-1"></i>
                                            {{ $contact->email }}
                                        </a>
                                    </p>
                                </div>
                            @endif
                            
                            @if($contact->company)
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Company/Organization</label>
                                    <p class="mb-0">
                                        <i class="bi bi-building me-1"></i>
                                        {{ $contact->company }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-journal-text me-2"></i>
                            Additional Information
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($contact->address)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Address</label>
                                <p class="mb-0">
                                    <i class="bi bi-geo-alt me-1"></i>
                                    {!! nl2br(e($contact->address)) !!}
                                </p>
                            </div>
                        @endif
                        
                        @if($contact->notes)
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Notes</label>
                                <div class="border rounded p-3 bg-light">
                                    {!! nl2br(e($contact->notes)) !!}
                                </div>
                            </div>
                        @endif
                        
                        @if(!$contact->address && !$contact->notes)
                            <div class="text-center py-4">
                                <i class="bi bi-info-circle text-muted display-6"></i>
                                <p class="text-muted mt-2 mb-0">No additional information available</p>
                                <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bi bi-plus me-1"></i>
                                    Add Information
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Activity -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Contact Activity
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-2">
                            <div class="badge bg-success rounded-pill me-3">
                                <i class="bi bi-plus"></i>
                            </div>
                            <div>
                                <div class="fw-semibold">Contact Created</div>
                                <small class="text-muted">{{ $contact->created_at->format('F j, Y \a\t g:i A') }}</small>
                                <br>
                                <small class="text-muted">{{ $contact->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                    
                    @if($contact->updated_at && $contact->updated_at->ne($contact->created_at))
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <div class="badge bg-primary rounded-pill me-3">
                                    <i class="bi bi-pencil"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">Last Updated</div>
                                    <small class="text-muted">{{ $contact->updated_at->format('F j, Y \a\t g:i A') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $contact->updated_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="d-flex gap-2 mt-4">
            <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Contacts
            </a>
            <a href="{{ route('contacts.edit', $contact) }}" class="btn btn-primary">
                <i class="bi bi-pencil me-1"></i>
                Edit Contact
            </a>
            @if($contact->email)
                <a href="mailto:{{ $contact->email }}" class="btn btn-outline-success">
                    <i class="bi bi-envelope me-1"></i>
                    Send Email
                </a>
            @endif
            <a href="tel:{{ $contact->phone }}" class="btn btn-outline-info">
                <i class="bi bi-telephone me-1"></i>
                Call Contact
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Confirm deletion
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm(`Are you sure you want to delete ${{{ json_encode($contact->name) }}}? This action cannot be undone.`)) {
                e.preventDefault();
            }
        });
    });

    // Copy contact information to clipboard
    function copyToClipboard(text, element) {
        navigator.clipboard.writeText(text).then(function() {
            // Show temporary feedback
            const originalText = element.innerHTML;
            element.innerHTML = '<i class="bi bi-check text-success"></i> Copied!';
            
            setTimeout(function() {
                element.innerHTML = originalText;
            }, 2000);
        });
    }

    // Make phone and email clickable for copying
    document.querySelectorAll('a[href^="tel:"], a[href^="mailto:"]').forEach(link => {
        link.addEventListener('dblclick', function(e) {
            e.preventDefault();
            const text = this.textContent.trim();
            copyToClipboard(text, this);
        });
        
        // Add tooltip
        link.setAttribute('title', 'Double-click to copy');
    });
});
</script>
@endpush
