@extends('layouts.app')

@section('title', 'Import Contacts - Contact Manager')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bi bi-upload me-2"></i>
                    Import Contacts from XML
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('contacts.process-import') }}" method="POST" enctype="multipart/form-data" id="importForm">
                    @csrf
                    
                    <!-- File Upload Area -->
                    <div class="import-drop-zone mb-4">
                        <div class="text-center">
                            <i class="bi bi-cloud-upload display-1 text-muted mb-3"></i>
                            <h5>Drop your XML file here or click to browse</h5>
                            <p class="text-muted mb-3">
                                Select an XML file containing contact information to import
                            </p>
                            
                            <input type="file" 
                                   class="form-control @error('xml_file') is-invalid @enderror" 
                                   id="xml_file" 
                                   name="xml_file" 
                                   accept=".xml,text/xml,application/xml"
                                   required
                                   style="display: none;">
                            
                            <label for="xml_file" class="btn btn-primary btn-lg">
                                <i class="bi bi-folder2-open me-2"></i>
                                Choose XML File
                                <span class="file-name ms-2 text-white-50"></span>
                            </label>
                            
                            @error('xml_file')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Import Options -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="bi bi-gear me-2"></i>
                                Import Options
                            </h6>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="skip_duplicates" name="skip_duplicates" checked>
                                <label class="form-check-label" for="skip_duplicates">
                                    Skip duplicate contacts (based on phone number)
                                </label>
                            </div>
                            <small class="text-muted">
                                Contacts with existing phone numbers will be skipped to avoid duplicates.
                            </small>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-upload me-2"></i>
                            Import Contacts
                        </button>
                        <a href="{{ route('contacts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Back to Contacts
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Expected XML Format -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-code-slash me-2"></i>
                    Expected XML Format
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Your XML file should follow this structure. Name and phone are required fields:
                </p>
                
                <pre class="bg-dark text-light p-3 rounded"><code>&lt;?xml version="1.0" encoding="UTF-8"?&gt;
&lt;contacts&gt;
    &lt;contact&gt;
        &lt;name&gt;John Doe&lt;/name&gt;
        &lt;phone&gt;+1 555 123 4567&lt;/phone&gt;
        &lt;email&gt;john@example.com&lt;/email&gt;
        &lt;company&gt;Acme Corporation&lt;/company&gt;
        &lt;address&gt;123 Main St, City, State&lt;/address&gt;
        &lt;notes&gt;Important client&lt;/notes&gt;
    &lt;/contact&gt;
    &lt;contact&gt;
        &lt;name&gt;Jane Smith&lt;/name&gt;
        &lt;phone&gt;+1 555 987 6543&lt;/phone&gt;
        &lt;!-- email, company, address, and notes are optional --&gt;
    &lt;/contact&gt;
&lt;/contacts&gt;</code></pre>
                
                <div class="alert alert-info mt-3">
                    <h6 class="alert-heading">
                        <i class="bi bi-info-circle me-2"></i>
                        Field Requirements
                    </h6>
                    <ul class="mb-0">
                        <li><strong>name</strong> - Required. Full contact name</li>
                        <li><strong>phone</strong> - Required. Phone number in any format</li>
                        <li><strong>email</strong> - Optional. Valid email address</li>
                        <li><strong>company</strong> - Optional. Company or organization name</li>
                        <li><strong>address</strong> - Optional. Full address</li>
                        <li><strong>notes</strong> - Optional. Additional notes</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Sample XML Download -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-download me-2"></i>
                    Sample Files
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Download a sample XML file to see the expected format:
                </p>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="downloadSampleXML()">
                        <i class="bi bi-download me-1"></i>
                        Download Sample XML
                    </button>
                    @if(\App\Models\Contact::count() > 0)
                        <a href="{{ route('contacts.export') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-file-earmark-arrow-down me-1"></i>
                            Export Current Contacts
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Import Statistics -->
        @if(\App\Models\Contact::count() > 0)
            <div class="card mt-4">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="mb-2">
                                <i class="bi bi-people text-primary display-6"></i>
                            </div>
                            <h5 class="text-primary">{{ \App\Models\Contact::count() }}</h5>
                            <p class="text-muted mb-0">Current Contacts</p>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <i class="bi bi-envelope text-success display-6"></i>
                            </div>
                            <h5 class="text-success">{{ \App\Models\Contact::whereNotNull('email')->count() }}</h5>
                            <p class="text-muted mb-0">With Email</p>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <i class="bi bi-building text-info display-6"></i>
                            </div>
                            <h5 class="text-info">{{ \App\Models\Contact::whereNotNull('company')->count() }}</h5>
                            <p class="text-muted mb-0">With Company</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('importForm');
    const fileInput = document.getElementById('xml_file');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // File validation
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB.');
                this.value = '';
                return;
            }
            
            // Basic file type check (allow various extensions)
            const allowedExtensions = ['.xml', '.txt'];
            const fileExtension = file.name.toLowerCase().substring(file.name.lastIndexOf('.'));
            
            if (!allowedExtensions.includes(fileExtension)) {
                // Still allow the file but warn user
                if (!confirm('This file doesn\'t have an .xml extension. Continue anyway?')) {
                    this.value = '';
                    return;
                }
            }
            
            // Update file name display
            const fileNameSpan = document.querySelector('.file-name');
            if (fileNameSpan) {
                fileNameSpan.textContent = file.name;
            }
        }
    });

    // Form submission with progress
    form.addEventListener('submit', function(e) {
        if (!fileInput.files.length) {
            e.preventDefault();
            alert('Please select an XML file to import.');
            return;
        }

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing Import...';
        
        // Show progress message
        const progressDiv = document.createElement('div');
        progressDiv.className = 'alert alert-info mt-3';
        progressDiv.innerHTML = '<i class="bi bi-info-circle me-2"></i>Processing your XML file. Please wait...';
        form.appendChild(progressDiv);
    });
});

// Download sample XML function
function downloadSampleXML() {
    const sampleXML = `<?xml version="1.0" encoding="UTF-8"?>
<contacts>
    <contact>
        <name>Kökten Adal</name>
        <phone>+90 333 8859342</phone>
    </contact>
    <contact>
        <name>Hamma Abdurrezak</name>
        <phone>+90 333 1563682</phone>
        <email>hamma@example.com</email>
        <company>Tech Solutions Inc.</company>
        <address>Istanbul, Turkey</address>
        <notes>Important business contact</notes>
    </contact>
    <contact>
        <name>Güleycan Şensal</name>
        <phone>+90 333 2557114</phone>
        <email>guleycan@example.com</email>
    </contact>
</contacts>`;

    const blob = new Blob([sampleXML], { type: 'application/xml' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'sample_contacts.xml';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}
</script>
@endpush
