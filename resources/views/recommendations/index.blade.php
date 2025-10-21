@extends('layouts/contentNavbarLayout')

@section('title', 'Recommendations')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold mb-3">
            Your <span class="text-primary">Recommendations</span>
        </h1>
        <p class="lead text-muted mb-4">Personalized suggestions based on your reviews</p>
        <a href="{{ route('recommendations.generate.get') }}" class="btn btn-lg px-4 py-2" 
           style="background-color: #FF3B30; border: 1px solid #FF3B30; color: white;">
            Generate Recommendations
        </a>
    </div>

    <!-- Recommendations Grid -->
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($recommendations->count() > 0)
            <div class="row g-4">
                @foreach($recommendations as $recommendation)
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 recommendation-card" 
                             data-recommendation-id="{{ $recommendation->id }}"
                             style="border-radius: 12px; transition: transform 0.2s ease;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <span class="badge bg-primary text-white px-3 py-2" style="border-radius: 8px; font-size: 0.75rem;">
                                        {{ $recommendation->source === 'AI' ? 'AI' : ucfirst($recommendation->source) }}
                                    </span>
                                    <small class="text-muted fw-medium">{{ number_format($recommendation->score * 100, 0) }}% match</small>
                                </div>
                                
                                <h5 class="card-title fw-bold mb-2" style="color: #333; line-height: 1.3;">
                                    {{ $recommendation->livre->title }}
                                </h5>
                                
                                @if($recommendation->livre->categorie)
                                    <span class="badge bg-dark text-white mb-3" style="border-radius: 6px; font-size: 0.7rem;">
                                        {{ $recommendation->livre->categorie->nom }}
                                    </span>
                                @endif
                                
                                @if($recommendation->reason)
                                    <p class="card-text small text-muted mb-3" style="line-height: 1.4;">
                                        {{ \Illuminate\Support\Str::limit($recommendation->reason, 80) }}
                                    </p>
                                @endif
                                
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        {{ $recommendation->date_creation->format('M d, Y') }}
                                    </small>
                                    <button onclick="readBook({{ $recommendation->livre->id }}, '{{ $recommendation->livre->title }}', '{{ $recommendation->livre->fichier_livre }}')" 
                                            class="btn btn-sm px-3 py-2" 
                                            style="background-color: #ff6b35; border: 1px solid #ff6b35; color: white; border-radius: 8px; font-weight: 500; box-shadow: 0 2px 4px rgba(255, 107, 53, 0.3);">
                                        <i class="bx bx-book me-1"></i>Read Book
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bx bx-book-open display-1 text-muted"></i>
                </div>
                <h4 class="text-muted mb-3">No recommendations yet</h4>
                <p class="text-muted mb-4">Start rating books to get personalized recommendations!</p>
                <a href="{{ route('recommendations.generate') }}" class="btn btn-lg px-4 py-2" 
                   style="background-color: #FF3B30; border: 1px solid #FF3B30; color: white;">
                    Generate Recommendations
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Book Reading Modal -->
<div class="modal fade" id="bookReadingModal" tabindex="-1" aria-labelledby="bookReadingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookReadingModalLabel">Reading: <span id="readingBookTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="bookContent" style="height: 70vh; overflow-y: auto;">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading book content...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <a id="downloadBookBtn" href="#" class="btn btn-primary" download>
                    <i class="bx bx-download me-1"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.recommendation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
}

.btn:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}
</style>

<!-- PDF.js for PDF viewing -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
// Configure PDF.js
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

// Read book function
function readBook(livreId, bookTitle, filePath) {
    console.log('readBook called with:', { livreId, bookTitle, filePath });
    
    document.getElementById('readingBookTitle').textContent = bookTitle;
    
    // Set up download link
    const downloadBtn = document.getElementById('downloadBookBtn');
    downloadBtn.href = `/storage/${filePath}`;
    downloadBtn.download = `${bookTitle}.${filePath.split('.').pop()}`;
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('bookReadingModal'));
    modal.show();
    
    // Load book content
    loadBookContent(livreId, filePath);
}

// Load book content
function loadBookContent(livreId, filePath) {
    const bookContent = document.getElementById('bookContent');
    bookContent.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading book content...</span></div></div>';
    
    const fileExtension = filePath.split('.').pop().toLowerCase();
    const fileUrl = `/storage/${filePath}`;
    
    switch(fileExtension) {
        case 'pdf':
            loadPDF(fileUrl, bookContent);
            break;
        case 'txt':
            loadTextFile(fileUrl, bookContent);
            break;
        default:
            bookContent.innerHTML = `
                <div class="text-center py-5">
                    <i class="bx bx-file display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">Preview not available</h5>
                    <p class="text-muted">This file format cannot be previewed. Please download to view.</p>
                    <a href="${fileUrl}" class="btn btn-primary" download>
                        <i class="bx bx-download me-1"></i> Download File
                    </a>
                </div>
            `;
    }
}

// Load PDF
function loadPDF(fileUrl, container) {
    pdfjsLib.getDocument(fileUrl).promise.then(function(pdf) {
        let pdfText = '';
        let pagePromises = [];
        
        for (let i = 1; i <= Math.min(pdf.numPages, 10); i++) {
            pagePromises.push(
                pdf.getPage(i).then(function(page) {
                    return page.getTextContent().then(function(textContent) {
                        let pageText = textContent.items.map(function(item) {
                            return item.str;
                        }).join(' ');
                        return `--- Page ${i} ---\n${pageText}\n\n`;
                    });
                })
            );
        }
        
        Promise.all(pagePromises).then(function(pages) {
            pdfText = pages.join('');
            container.innerHTML = `
                <div class="pdf-content">
                    <pre style="white-space: pre-wrap; font-family: Arial, sans-serif; line-height: 1.6; margin: 0;">${pdfText}</pre>
                </div>
            `;
        });
    }).catch(function(error) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="bx bx-error display-4 text-danger mb-3"></i>
                <h5 class="text-danger">Error loading PDF</h5>
                <p class="text-muted">Unable to load the PDF file. Please try downloading it instead.</p>
                <a href="${fileUrl}" class="btn btn-primary" download>
                    <i class="bx bx-download me-1"></i> Download PDF
                </a>
            </div>
        `;
    });
}

// Load text file
function loadTextFile(fileUrl, container) {
    fetch(fileUrl)
        .then(response => response.text())
        .then(text => {
            container.innerHTML = `
                <div class="text-content">
                    <pre style="white-space: pre-wrap; font-family: Arial, sans-serif; line-height: 1.6; margin: 0;">${text}</pre>
                </div>
            `;
        })
        .catch(error => {
            container.innerHTML = `
                <div class="text-center py-5">
                    <i class="bx bx-error display-4 text-danger mb-3"></i>
                    <h5 class="text-danger">Error loading text file</h5>
                    <p class="text-muted">Unable to load the text file. Please try downloading it instead.</p>
                    <a href="${fileUrl}" class="btn btn-primary" download>
                        <i class="bx bx-download me-1"></i> Download File
                    </a>
                </div>
            `;
        });
}
</script>
@endsection
