@extends('layouts/contentNavbarLayout')

@section('title', ucfirst($source) . ' Recommendations')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">{{ ucfirst($source) }} Recommendations</h5>
                        <p class="text-muted mb-0">{{ $recommendations->count() }} recommendations found</p>
                    </div>
                    <a href="{{ route('recommendations.index') }}" class="btn btn-outline-secondary">
                        <i class="bx bx-arrow-back me-1"></i> Back to All Recommendations
                    </a>
                </div>
                <div class="card-body">
                    @if($recommendations->count() > 0)
                        <div class="row">
                            @foreach($recommendations as $recommendation)
                                <div class="col-md-6 col-lg-4 mb-4">
                                    <div class="card h-100 recommendation-card {{ $recommendation->is_viewed ? '' : 'border-primary' }}" 
                                         data-recommendation-id="{{ $recommendation->id }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <span class="badge bg-{{ $recommendation->source === 'AI' ? 'info' : ($recommendation->source === 'collaborative' ? 'success' : 'secondary') }}">
                                                    {{ ucfirst($recommendation->source) }}
                                                </span>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item mark-viewed" href="#" data-id="{{ $recommendation->id }}">
                                                                <i class="bx bx-check me-2"></i>Mark as Viewed
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="#" 
                                                               onclick="deleteRecommendation({{ $recommendation->id }})">
                                                                <i class="bx bx-trash me-2"></i>Remove
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            
                                            <h6 class="card-title">{{ $recommendation->livre->title }}</h6>
                                            <p class="text-muted small mb-2">by {{ $recommendation->livre->author }}</p>
                                            
                                            @if($recommendation->livre->categorie)
                                                <span class="badge bg-light text-dark mb-2">{{ $recommendation->livre->categorie->nom }}</span>
                                            @endif
                                            
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar bg-primary" style="width: {{ $recommendation->score * 100 }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ number_format($recommendation->score * 100, 1) }}% match</small>
                                            </div>
                                            
                                            @if($recommendation->reason)
                                                <p class="card-text small text-muted">{{ \\Illuminate\\Support\\Str::limit($recommendation->reason, 100) }}</p>
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
                            <i class="bx bx-book-open display-4 text-muted mb-3"></i>
                            <h5 class="text-muted">No {{ strtolower($source) }} recommendations found</h5>
                            <p class="text-muted">Try generating new recommendations or check other sources.</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('recommendations.generate.get') }}" class="btn btn-primary">
                                    Generate Recommendations
                                </a>
                                <a href="{{ route('recommendations.index') }}" class="btn btn-outline-secondary">
                                    View All Recommendations
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteRecommendation(id) {
    if (confirm('Are you sure you want to remove this recommendation?')) {
        fetch(`/recommendations/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}

// Mark as viewed functionality
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.mark-viewed').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const recommendationId = this.getAttribute('data-id');
            
            fetch(`/recommendations/${recommendationId}/mark-viewed`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const card = document.querySelector(`[data-recommendation-id="${recommendationId}"]`);
                    card.classList.remove('border-primary');
                    this.closest('.dropdown-menu').querySelector('.mark-viewed').style.display = 'none';
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>

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
