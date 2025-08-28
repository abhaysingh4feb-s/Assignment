<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Contact Manager')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }
        
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border: 1px solid rgba(0, 0, 0, 0.125);
        }
        
        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid rgba(0, 0, 0, 0.125);
            font-weight: 600;
        }
        
        .btn-group .btn {
            margin-right: 0.25rem;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
        
        .btn-group .btn:last-child {
            margin-right: 0;
        }
        
        .navbar .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .table th {
            border-top: none;
            font-weight: 600;
            background-color: #f8f9fa;
        }
        
        .contact-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .search-form {
            background-color: #f8f9fa;
            padding: 1.5rem;
            border-radius: 0.375rem;
            margin-bottom: 1.5rem;
        }
        
        .action-buttons {
            white-space: nowrap;
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .contact-info {
            line-height: 1.4;
        }
        
        .contact-phone {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .contact-email {
            color: #198754;
            font-size: 0.9rem;
        }
        
        .import-drop-zone {
            border: 2px dashed #dee2e6;
            border-radius: 0.375rem;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
        }
        
        .import-drop-zone:hover,
        .import-drop-zone.dragover {
            border-color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.05);
        }
        
        .sort-link {
            color: inherit;
            text-decoration: none;
        }
        
        .sort-link:hover {
            color: #0d6efd;
        }
        
        .sort-icon {
            font-size: 0.8rem;
            margin-left: 0.25rem;
        }
        
        /* Pagination styling */
        .pagination {
            margin-bottom: 0;
        }
        
        .pagination-sm .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 0.2rem;
            margin: 0 0.05rem;
            min-width: 32px;
            text-align: center;
        }
        
        .pagination .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
            border-radius: 0.25rem;
            margin: 0 0.1rem;
            min-width: 36px;
            text-align: center;
            color: #495057;
            border: 1px solid #dee2e6;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: white;
        }
        
        .pagination .page-item.disabled .page-link {
            color: #6c757d;
            background-color: #fff;
        }
        
        .pagination .page-link:hover {
            color: #0d6efd;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="{{ route('contacts.index') }}">
                <i class="bi bi-person-lines-fill me-2"></i>
                Contact Manager
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contacts.index') ? 'active' : '' }}" 
                           href="{{ route('contacts.index') }}">
                            <i class="bi bi-list-ul me-1"></i>
                            <span class="d-none d-md-inline">All </span>Contacts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contacts.create') ? 'active' : '' }}" 
                           href="{{ route('contacts.create') }}">
                            <i class="bi bi-person-plus me-1"></i>
                            <span class="d-none d-md-inline">Add </span>Contact
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contacts.import') ? 'active' : '' }}" 
                           href="{{ route('contacts.import') }}">
                            <i class="bi bi-upload me-1"></i>
                            Import
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contacts.export') }}">
                            <i class="bi bi-download me-1"></i>
                            Export
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mt-4">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('import_errors') && count(session('import_errors')) > 0)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h6 class="alert-heading">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Import Warnings
                </h6>
                <ul class="mb-0">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Page Content -->
        <div class="fade-in">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-5 py-4 bg-white border-top">
        <div class="container">
            <div class="row align-items-center">
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    if (alert && alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                }, 5000);
            });

            // Confirm deletion
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    if (!confirm('Are you sure you want to delete this contact?')) {
                        e.preventDefault();
                    }
                });
            });

            // File upload drag and drop
            const dropZone = document.querySelector('.import-drop-zone');
            if (dropZone) {
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, preventDefaults, false);
                });

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropZone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropZone.addEventListener(eventName, unhighlight, false);
                });

                dropZone.addEventListener('drop', handleDrop, false);

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                function highlight(e) {
                    dropZone.classList.add('dragover');
                }

                function unhighlight(e) {
                    dropZone.classList.remove('dragover');
                }

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    const fileInput = document.querySelector('#xml_file');
                    
                    if (files.length > 0 && fileInput) {
                        fileInput.files = files;
                        const label = document.querySelector('label[for="xml_file"] .file-name');
                        if (label) {
                            label.textContent = files[0].name;
                        }
                    }
                }
            }

            // File input change handler
            const fileInput = document.querySelector('#xml_file');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    const label = document.querySelector('label[for="xml_file"] .file-name');
                    if (label && this.files.length > 0) {
                        label.textContent = this.files[0].name;
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
