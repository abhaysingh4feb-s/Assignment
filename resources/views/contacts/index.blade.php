@extends('layouts.app')

@section('title', 'Contacts - Contact Manager')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">
        <i class="bi bi-person-lines-fill me-2"></i>
        Contact Directory
    </h1>
    <div class="btn-group">
        <a href="{{ route('contacts.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-person-plus me-1"></i>
            <span class="d-none d-sm-inline">Add </span>Contact
        </a>
        <a href="{{ route('contacts.import') }}" class="btn btn-outline-primary btn-sm">
            <i class="bi bi-upload me-1"></i>
            Import
        </a>
        <a href="{{ route('contacts.export') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-download me-1"></i>
            Export
        </a>
    </div>
</div>

<!-- Search and Filter Form -->
<div class="search-form">
    <form method="GET" action="{{ route('contacts.index') }}" class="row g-3">
        <div class="col-md-8">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Search contacts by name, phone, email, or company...">
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-grid gap-2 d-md-flex">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-search me-1"></i>
                    Search
                </button>
                @if(request('search'))
                    <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle me-1"></i>
                        Clear
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Hidden fields to maintain sorting -->
        @if(request('sort'))
            <input type="hidden" name="sort" value="{{ request('sort') }}">
        @endif
        @if(request('direction'))
            <input type="hidden" name="direction" value="{{ request('direction') }}">
        @endif
    </form>
</div>

<!-- Contacts Statistics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $contacts->total() }}</h5>
                <p class="card-text text-muted mb-0">Total Contacts</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">{{ $contacts->where('email', '!=', null)->count() }}</h5>
                <p class="card-text text-muted mb-0">With Email</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">{{ $contacts->where('company', '!=', null)->count() }}</h5>
                <p class="card-text text-muted mb-0">With Company</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning">{{ $contacts->count() }}</h5>
                <p class="card-text text-muted mb-0">Current Page</p>
            </div>
        </div>
    </div>
</div>

<!-- Contacts Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>
            <i class="bi bi-table me-2"></i>
            Contacts
            @if(request('search'))
                <small class="text-muted">- Search results for "{{ request('search') }}"</small>
            @endif
        </span>
        <span class="badge bg-primary">{{ $contacts->total() }} total</span>
    </div>
    <div class="card-body p-0">
        @if($contacts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th scope="col">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'direction' => ($sortField === 'name' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" 
                                   class="sort-link">
                                    Contact
                                    @if($sortField === 'name')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'phone', 'direction' => ($sortField === 'phone' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" 
                                   class="sort-link">
                                    Phone
                                    @if($sortField === 'phone')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'email', 'direction' => ($sortField === 'email' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" 
                                   class="sort-link">
                                    Email
                                    @if($sortField === 'email')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'company', 'direction' => ($sortField === 'company' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" 
                                   class="sort-link">
                                    Company
                                    @if($sortField === 'company')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col">
                                <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'direction' => ($sortField === 'created_at' && $sortDirection === 'asc') ? 'desc' : 'asc']) }}" 
                                   class="sort-link">
                                    Added
                                    @if($sortField === 'created_at')
                                        <i class="bi bi-arrow-{{ $sortDirection === 'asc' ? 'up' : 'down' }} sort-icon"></i>
                                    @endif
                                </a>
                            </th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="contact-avatar me-3">
                                            {{ strtoupper(substr($contact->name, 0, 1)) }}
                                        </div>
                                        <div class="contact-info">
                                            <div class="fw-semibold">{{ $contact->name }}</div>
                                            @if($contact->company)
                                                <small class="text-muted">{{ $contact->company }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="tel:{{ $contact->phone }}" class="contact-phone text-decoration-none">
                                        <i class="bi bi-telephone me-1"></i>
                                        {{ $contact->phone }}
                                    </a>
                                </td>
                                <td>
                                    @if($contact->email)
                                        <a href="mailto:{{ $contact->email }}" class="contact-email text-decoration-none">
                                            <i class="bi bi-envelope me-1"></i>
                                            {{ $contact->email }}
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($contact->company)
                                        <span class="text-muted">{{ $contact->company }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $contact->created_at->format('M j, Y') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="action-buttons text-end">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('contacts.show', $contact) }}" 
                                               class="btn btn-outline-info" 
                                               title="View Contact">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('contacts.edit', $contact) }}" 
                                               class="btn btn-outline-primary" 
                                               title="Edit Contact">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <form action="{{ route('contacts.destroy', $contact) }}" 
                                                  method="POST" 
                                                  style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-outline-danger delete-btn" 
                                                        title="Delete Contact">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-3">
                    <i class="bi bi-person-x display-1 text-muted"></i>
                </div>
                <h5 class="text-muted">No contacts found</h5>
                <p class="text-muted mb-3">
                    @if(request('search'))
                        No contacts match your search criteria.
                    @else
                        You haven't added any contacts yet.
                    @endif
                </p>
                <div class="d-flex gap-2 justify-content-center">
                    @if(request('search'))
                        <a href="{{ route('contacts.index') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Clear Search
                        </a>
                    @endif
                    <a href="{{ route('contacts.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus me-1"></i>
                        Add Your First Contact
                    </a>
                    <a href="{{ route('contacts.import') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-upload me-1"></i>
                        Import from XML
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
@if($contacts->hasPages())
    <div class="mt-4">
        {{ $contacts->links('custom.pagination') }}
    </div>
@endif
@endsection
