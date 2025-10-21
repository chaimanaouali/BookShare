@extends('front.layouts.app')
@section('title','Explore Public Libraries')
@include('front.partials.header')

<style>
.comment-card {
    background: #fff;
    border: 1px solid #e1e5e9;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
    margin-bottom: 16px;
}

.comment-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-1px);
}

.comment-header {
    padding: 16px 20px 12px;
    border-bottom: 1px solid #f1f3f4;
}

.comment-body {
    padding: 12px 20px 16px;
}

.comment-meta {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 8px;
}

.comment-content {
    line-height: 1.6;
    color: #333;
    margin-bottom: 12px;
}

.vote-section {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 12px;
}

.vote-btn {
    background: #fff;
    border: 1px solid #dee2e6;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06);
}

.vote-btn:hover {
    background: #f8f9fa;
    border-color: #adb5bd;
    transform: translateY(-1px);
}

.vote-btn.upvote:hover {
    color: #28a745;
    border-color: #28a745;
}

.vote-btn.downvote:hover {
    color: #dc3545;
    border-color: #dc3545;
}

.vote-btn.active.upvote {
    background: #d4edda;
    color: #28a745;
    border-color: #28a745;
}

.vote-btn.active.downvote {
    background: #f8d7da;
    color: #dc3545;
    border-color: #dc3545;
}

.vote-score {
    font-weight: 600;
    color: #495057;
    min-width: 20px;
    text-align: center;
}

.reply-section {
    margin-left: 24px;
    border-left: 3px solid #e9ecef;
    padding-left: 16px;
}

.sort-controls {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 20px;
}

.sort-btn {
    background: none;
    border: 1px solid #dee2e6;
    padding: 6px 12px;
    border-radius: 6px;
    margin-right: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 0.875rem;
}

.sort-btn:hover {
    background: #e9ecef;
}

.sort-btn.active {
    background: #007bff;
    color: white;
    border-color: #007bff;
}

  .discussion-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px 12px 0 0;
    cursor: default; 
  }

  /* AI Summary Styles */
  .ai-summary-section {
    background: linear-gradient(135deg, #f8f9ff 0%, #e8f2ff 100%);
    border: 1px solid #e3f2fd;
    border-radius: 8px;
    padding: 15px;
  }

  .ai-summary-content {
    min-height: 40px;
  }

  .ai-summary-content .summary-text {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    padding: 12px;
    font-size: 0.9rem;
    line-height: 1.5;
    color: #333;
  }

  .ai-summary-content .loading {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #666;
    font-style: italic;
  }

  .ai-summary-content .error {
    color: #dc3545;
    font-size: 0.85rem;
  }

  .summarize-btn {
    transition: all 0.2s ease;
  }

  .summarize-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .summarize-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
  }

.comment-form {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
    margin-top: 16px;
}

.reply-form {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 12px;
    margin-top: 12px;
    display: none;
}

.loading {
    opacity: 0.6;
    pointer-events: none;
}

.btn-primary {
    background-color: #ff7a00;
    border-color: #ff7a00;
    padding: 10px 18px;
    border-radius: 24px;
    font-weight: 600;
}

.btn-primary:hover {
    background-color: #e56f00;
    border-color: #e56f00;
}

.btn-link {
    color: #007bff;
    text-decoration: none;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.2s ease;
}

.btn-link:hover {
    color: #0056b3;
    background-color: #f8f9fa;
}
</style>

<div class="container py-4" style="margin-top:100px;">
  <h2 class="mb-2 fw-bold text-primary">{{ $bibliotheque->nom_bibliotheque }}</h2>
  <p class="text-muted">Owner: {{ $bibliotheque->user->name ?? 'Unknown' }}</p>
  
  <!-- Books Section -->
  <div class="card mt-4 shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0">Public Books</h5>
    </div>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Title</th>
            <th>Author</th>
            <th>File</th>
            <th>Uploaded</th>
            <th>Download</th>
            @auth
              <th>Favorite</th>
            @endauth
          </tr>
        </thead>
        <tbody>
          @forelse($bibliotheque->livreUtilisateurs as $livre)
            <tr>
              <td><i class="bx bx-book me-1 text-warning"></i> {{ $livre->title ?? 'Untitled' }}</td>
              <td>{{ $livre->author ?? 'Unknown' }}</td>
              <td><span class="badge bg-label-secondary">{{ $livre->fichier_livre }}</span></td>
              <td>{{ $livre->created_at->diffForHumans() }}</td>
              <td>
                @php
                  $fileUrl = $livre->fichier_livre ? Storage::url($livre->fichier_livre) : null;
                @endphp
                @if($fileUrl)
                  <a href="{{ $fileUrl }}" class="btn btn-sm btn-outline-success" download>
                    <i class="bx bx-download"></i> Download
                  </a>
                @else
                  <span class="text-muted">N/A</span>
                @endif
              </td>
              @auth
                <td>
                  <x-favorite-button-inline :livre="$livre" :size="'sm'" />
                </td>
              @endauth
            </tr>
          @empty
            <tr><td colspan="{{ auth()->check() ? '6' : '5' }}" class="text-muted">No public books in this library.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Discussions Section -->
  <div class="card mt-4 shadow-sm">
    <div class="discussion-header d-flex justify-content-between align-items-center p-3 discussion-toggle">
      <h5 class="mb-0"><i class="bx bx-message-square-dots me-2"></i>Library Discussions</h5>
      <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#newDiscussionModal">
        <i class="bx bx-plus"></i> Start Discussion
      </button>
    </div>
    <div class="card-body p-0 discussions-body">
      @forelse($bibliotheque->discussions as $discussion)
        <div class="discussion-item p-4 border-bottom">
          <div class="d-flex justify-content-between align-items-center mb-3 discussion-item-header" role="button">
            <div class="flex-grow-1">
              <h6 class="mb-1 fw-bold">
                <i class="bx bx-message-square me-1"></i>
                {{ $discussion->titre }}
                @if($discussion->est_resolu)
                  <span class="badge bg-success ms-2">Resolved</span>
                @else
                  <span class="badge bg-warning ms-2">Open</span>
                @endif
              </h6>
              <small class="text-muted">
                by <strong>{{ $discussion->user->name }}</strong> • {{ $discussion->created_at->diffForHumans() }}
              </small>
            </div>
            <div class="d-flex align-items-center gap-2 ms-auto">
              <!-- Sort Dropdown -->
              <div class="dropdown me-2 flex-shrink-0">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bx bx-sort"></i> Sort
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li><button class="dropdown-item sort-option active" type="button" data-sort="newest" data-discussion="{{ $discussion->id }}"><i class="bx bx-time me-2"></i>Newest</button></li>
                  <li><button class="dropdown-item sort-option" type="button" data-sort="score" data-discussion="{{ $discussion->id }}"><i class="bx bx-trending-up me-2"></i>Most Popular</button></li>
                  <li><button class="dropdown-item sort-option" type="button" data-sort="most_active" data-discussion="{{ $discussion->id }}"><i class="bx bx-message-dots me-2"></i>Most Active</button></li>
                </ul>
              </div>

              @if(Auth::id() === $discussion->user_id || Auth::id() === $bibliotheque->user_id)
                <div class="btn-group btn-group-sm flex-shrink-0">
                  @if($discussion->est_resolu)
                    <button type="button" class="btn btn-outline-warning btn-sm resolve-btn" data-action="unresolve" data-id="{{ $discussion->id }}">
                      <i class="bx bx-x"></i> Mark Unresolved
                    </button>
                  @else
                    <button type="button" class="btn btn-outline-success btn-sm resolve-btn" data-action="resolve" data-id="{{ $discussion->id }}">
                      <i class="bx bx-check"></i> Mark Resolved
                    </button>
                  @endif
                </div>
              @endif
            </div>
          </div>

          <div class="discussion-item-body">
            <p class="mb-3">{{ $discussion->contenu }}</p>

            <!-- AI Summary Section -->
            <div class="ai-summary-section mb-3" data-discussion="{{ $discussion->id }}">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0 text-primary">
                  <i class="bx bx-brain me-1"></i> AI Summary
                </h6>
                <button type="button" class="btn btn-sm btn-outline-primary summarize-btn" data-discussion="{{ $discussion->id }}">
                  <i class="bx bx-refresh me-1"></i> Generate Summary
                </button>
              </div>
              <div class="ai-summary-content" id="summary-{{ $discussion->id }}">
                <div class="text-muted small">
                  <i class="bx bx-info-circle me-1"></i>
                  Click "Generate Summary" to get an AI-powered analysis of this discussion.
                </div>
              </div>
            </div>

            <!-- Sort Controls moved to header dropdown -->

            <!-- Comments Section -->
            <div class="comments-container" data-discussion="{{ $discussion->id }}">
              @foreach($discussion->topLevelComments as $comment)
                @include('front.partials.comment', ['comment' => $comment, 'discussion' => $discussion])
              @endforeach
            </div>

            <!-- Add Comment Form -->
            <div class="comment-form">
              <form class="comment-form-ajax" data-discussion="{{ $discussion->id }}" novalidate>
                @csrf
                <div class="d-flex gap-2">
                  <div class="flex-grow-1">
                    <textarea name="contenu" 
                              class="form-control @error('contenu') is-invalid @enderror" 
                              rows="2" 
                              placeholder="Add a comment..." 
                              required
                              minlength="3"
                              maxlength="2000"
                              data-validation="required|min:3|max:2000|regex:^[a-zA-Z0-9\s\-_.,!?()\n\r]+$"></textarea>
                    @error('contenu')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="invalid-feedback" id="comment_error_{{ $discussion->id }}"></div>
                    <div class="form-text">
                      <span class="text-muted">(<span id="comment_count_{{ $discussion->id }}">0</span>/2000 characters)</span>
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary align-self-end" id="comment_submit_{{ $discussion->id }}">
                    <i class="bx bx-send"></i>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      @empty
        <div class="text-center py-5">
          <i class="bx bx-message-square-dots display-4 text-muted"></i>
          <p class="text-muted mt-2">No discussions yet. Be the first to start a conversation!</p>
        </div>
      @endforelse
    </div>
  </div>
</div>

<!-- New Discussion Modal -->
<div class="modal fade" id="newDiscussionModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Start New Discussion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="{{ route('discussions.store', $bibliotheque->id) }}" id="discussionForm" novalidate>
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label for="titre" class="form-label">Title <span class="text-danger">*</span></label>
            <input type="text" 
                   class="form-control @error('titre') is-invalid @enderror" 
                   id="titre" 
                   name="titre" 
                   value="{{ old('titre') }}"
                   required
                   minlength="5"
                   maxlength="255"
                   pattern="[a-zA-Z0-9\s\-_.,!?()]+"
                   data-validation="required|min:5|max:255|regex:^[a-zA-Z0-9\s\-_.,!?()]+$">
            @error('titre')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="invalid-feedback" id="titre_error"></div>
            <div class="form-text">
              Choose a descriptive title for your discussion
              <span class="text-muted">(<span id="titre_count">0</span>/255 characters)</span>
            </div>
          </div>
          <div class="mb-3">
            <label for="contenu" class="form-label">Content <span class="text-danger">*</span></label>
            <textarea class="form-control @error('contenu') is-invalid @enderror" 
                      id="contenu" 
                      name="contenu" 
                      rows="4" 
                      required
                      minlength="10"
                      maxlength="5000"
                      data-validation="required|min:10|max:5000|regex:^[a-zA-Z0-9\s\-_.,!?()\n\r]+$">{{ old('contenu') }}</textarea>
            @error('contenu')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="invalid-feedback" id="contenu_error"></div>
            <div class="form-text">
              Describe your discussion topic in detail
              <span class="text-muted">(<span id="contenu_count">0</span>/5000 characters)</span>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary" id="submitDiscussionBtn">
            <i class="bx bx-plus me-1"></i> Start Discussion
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// AJAX Comment Submission
document.addEventListener('DOMContentLoaded', function() {
    // Toggle individual discussion body when its header is clicked
    document.addEventListener('click', function(e) {
        const header = e.target.closest('.discussion-item-header');
        if (!header) return;
        // Avoid toggling when clicking buttons inside header
        if (e.target.closest('button, form')) return;
        const wrapper = header.closest('.discussion-item');
        const body = wrapper ? wrapper.querySelector('.discussion-item-body') : null;
        if (body) {
            body.style.display = body.style.display === 'none' ? 'block' : 'none';
        }
    });

    // Handle comment/reply submissions via event delegation so new forms also work
    document.addEventListener('submit', function(e) {
        const form = e.target.closest('.comment-form-ajax, .reply-form-ajax');
        if (!form) return;
        e.preventDefault();

        const discussionId = form.dataset.discussion;
        const parentId = form.dataset.parent;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;

        submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';
        submitBtn.disabled = true;

        fetch(`/discussions/${discussionId}/comments`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (parentId) {
                    const parentComment = document.querySelector(`[data-comment="${parentId}"]`);
                    const replySection = parentComment.querySelector('.reply-section') || createReplySection(parentComment);
                    const newReplyHtml = createReplyHtml(data.comment, discussionId);
                    replySection.insertAdjacentHTML('beforeend', newReplyHtml);
                } else {
                    const commentsContainer = document.querySelector(`.comments-container[data-discussion="${discussionId}"]`);
                    if (commentsContainer) {
                        const newCommentHtml = createCommentHtml(data.comment, discussionId);
                        commentsContainer.insertAdjacentHTML('beforeend', newCommentHtml);
                    }
                }
                form.querySelector('textarea').value = '';
                if (parentId) {
                    form.closest('.reply-form').style.display = 'none';
                }
                showNotification('Comment added successfully!', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error adding comment. Please try again.', 'error');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Handle voting (delegation)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.vote-btn');
        if (!btn) return;
        e.preventDefault();

        const commentId = btn.dataset.comment;
        const voteType = btn.dataset.vote;
        const discussionId = btn.dataset.discussion;

        let newVoteType = voteType;
        if (btn.classList.contains('active')) newVoteType = 'remove';

        fetch(`/comments/${commentId}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ vote_type: newVoteType })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) updateVoteDisplay(commentId, data.comment, data.user_vote);
        })
        .catch(() => showNotification('Error voting. Please try again.', 'error'));
    });
    
    // Handle sorting via dropdown (delegation) - use buttons to avoid page jump
    document.addEventListener('click', function(e) {
        const option = e.target.closest('.sort-option');
        if (!option) return;
        const discussionId = option.dataset.discussion;
        const sortBy = option.dataset.sort;

        const menu = option.closest('.dropdown-menu');
        if (menu) menu.querySelectorAll('.sort-option').forEach(a => a.classList.remove('active'));
        option.classList.add('active');

        loadSortedComments(discussionId, sortBy);
    });

    // Handle resolve/unresolve via AJAX (delegation)
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.resolve-btn');
        if (!btn) return;
        e.preventDefault();
        const action = btn.dataset.action; // 'resolve' or 'unresolve'
        const id = btn.dataset.id;
        const url = action === 'resolve' ? `/discussions/${id}/resolve` : `/discussions/${id}/unresolve`;

        btn.disabled = true;
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i>';

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(r => r.ok ? r : Promise.reject(r))
        .then(() => {
            // Toggle badges and button state inline
            const header = btn.closest('.discussion-item').querySelector('h6');
            if (!header) return;
            const openBadge = header.querySelector('.badge.bg-warning');
            const resolvedBadge = header.querySelector('.badge.bg-success');
            if (action === 'resolve') {
                if (openBadge) openBadge.classList.remove('bg-warning'), openBadge.classList.add('d-none');
                if (resolvedBadge) resolvedBadge.classList.remove('d-none');
                else header.insertAdjacentHTML('beforeend', ' <span class="badge bg-success ms-2">Resolved</span>');
                btn.classList.remove('btn-outline-success');
                btn.classList.add('btn-outline-warning');
                btn.dataset.action = 'unresolve';
                btn.innerHTML = '<i class="bx bx-x"></i> Mark Unresolved';
            } else {
                if (resolvedBadge) resolvedBadge.classList.add('d-none');
                if (openBadge) openBadge.classList.remove('d-none');
                else header.insertAdjacentHTML('beforeend', ' <span class="badge bg-warning ms-2">Open</span>');
                btn.classList.remove('btn-outline-warning');
                btn.classList.add('btn-outline-success');
                btn.dataset.action = 'resolve';
                btn.innerHTML = '<i class="bx bx-check"></i> Mark Resolved';
            }
            showNotification('Status updated', 'success');
        })
        .catch(() => showNotification('Failed to update status.', 'error'))
        .finally(() => {
            btn.disabled = false;
            if (!btn.innerHTML || btn.innerHTML.includes('bx-loader-alt')) btn.innerHTML = original;
        });
    });
});

function createCommentHtml(comment, discussionId) {
    return `
        <div class="comment-card" data-comment="${comment.id}">
            <div class="comment-header">
                <div class="comment-meta">
                    <strong>${comment.user.name}</strong> • ${formatTime(comment.created_at)}
                </div>
            </div>
            <div class="comment-body">
                <div class="comment-content">${comment.contenu}</div>
                <div class="vote-section">
                    <button class="vote-btn upvote" data-comment="${comment.id}" data-vote="upvote" data-discussion="${discussionId}">
                        <i class="bx bx-up-arrow-alt"></i>
                    </button>
                    <span class="vote-score">${comment.score || 0}</span>
                    <button class="vote-btn downvote" data-comment="${comment.id}" data-vote="downvote" data-discussion="${discussionId}">
                        <i class="bx bx-down-arrow-alt"></i>
                    </button>
                    <button class="btn btn-link btn-sm p-0 text-primary ms-3" onclick="toggleReplyForm(${comment.id})">
                        <i class="bx bx-reply"></i> Reply
                    </button>
                    <button class="btn btn-link btn-sm p-0 text-danger ms-2 delete-comment-btn" data-comment="${comment.id}">
                        <i class="bx bx-trash"></i> Delete
                    </button>
                </div>
                <div id="reply-form-${comment.id}" class="reply-form">
                    <form class="reply-form-ajax" data-discussion="${discussionId}" data-parent="${comment.id}">
                        <input type="hidden" name="parent_id" value="${comment.id}">
                        <div class="d-flex gap-2">
                            <textarea name="contenu" class="form-control form-control-sm" rows="2" placeholder="Write a reply..." required></textarea>
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bx bx-send"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    `;
}

function createReplyHtml(comment, discussionId) {
    return `
        <div class="comment-card">
            <div class="comment-header">
                <div class="comment-meta">
                    <strong>${comment.user.name}</strong> • ${formatTime(comment.created_at)}
                </div>
            </div>
            <div class="comment-body">
                <div class="comment-content">${comment.contenu}</div>
                <div class="vote-section">
                    <button class="vote-btn upvote" data-comment="${comment.id}" data-vote="upvote" data-discussion="${discussionId}">
                        <i class="bx bx-up-arrow-alt"></i>
                    </button>
                    <span class="vote-score">${comment.score || 0}</span>
                    <button class="vote-btn downvote" data-comment="${comment.id}" data-vote="downvote" data-discussion="${discussionId}">
                        <i class="bx bx-down-arrow-alt"></i>
                    </button>
                    <button class="btn btn-link btn-sm p-0 text-danger ms-2 delete-comment-btn" data-comment="${comment.id}">
                        <i class="bx bx-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    `;
}

function createReplySection(parentComment) {
    const replySection = document.createElement('div');
    replySection.className = 'reply-section';
    parentComment.querySelector('.comment-body').appendChild(replySection);
    return replySection;
}

function updateVoteDisplay(commentId, comment, userVote) {
    const commentCard = document.querySelector(`[data-comment="${commentId}"]`);
    const upvoteBtn = commentCard.querySelector('.upvote');
    const downvoteBtn = commentCard.querySelector('.downvote');
    const scoreSpan = commentCard.querySelector('.vote-score');
    
    // Update score
    scoreSpan.textContent = comment.score || 0;
    
    // Update button states
    upvoteBtn.classList.remove('active');
    downvoteBtn.classList.remove('active');
    
    if (userVote) {
        if (userVote.vote_type === 'upvote') {
            upvoteBtn.classList.add('active');
        } else if (userVote.vote_type === 'downvote') {
            downvoteBtn.classList.add('active');
        }
    }
}

function loadSortedComments(discussionId, sortBy) {
    const commentsContainer = document.querySelector(`.comments-container[data-discussion="${discussionId}"]`);
    if (!commentsContainer) return;

    commentsContainer.classList.add('loading');

    fetch(`/discussions/${discussionId}/comments?sort=${sortBy}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && Array.isArray(data.comments)) {
            commentsContainer.innerHTML = '';
            data.comments.forEach(comment => {
                const commentHtml = createCommentHtml(comment, discussionId);
                commentsContainer.insertAdjacentHTML('beforeend', commentHtml);
            });
        }
    })
    .catch(error => {
        console.error('Error loading sorted comments:', error);
        showNotification('Error loading sorted comments.', 'error');
    })
    .finally(() => {
        commentsContainer.classList.remove('loading');
    });
}


function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    if (form.style.display === 'none') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

function formatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date;
    
    if (diff < 60000) return 'just now';
    if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
    if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
    return Math.floor(diff / 86400000) + 'd ago';
}

function showNotification(message, type) {
    // Simple notification - you can enhance this with a proper notification library
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// AI Summary functionality
document.addEventListener('DOMContentLoaded', function() {
    // Handle summarize button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.summarize-btn')) {
            e.preventDefault();
            const button = e.target.closest('.summarize-btn');
            const discussionId = button.dataset.discussion;
            generateSummary(discussionId, button);
        }
    });

    // Handle delete comment button clicks
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-comment-btn')) {
            e.preventDefault();
            const button = e.target.closest('.delete-comment-btn');
            const commentId = button.dataset.comment;
            deleteComment(commentId, button);
        }
    });
});

function generateSummary(discussionId, button) {
    const summaryContent = document.getElementById(`summary-${discussionId}`);
    const originalText = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Generating...';
    
    summaryContent.innerHTML = `
        <div class="loading">
            <i class="bx bx-loader-alt bx-spin"></i>
            Analyzing discussion and generating summary...
        </div>
    `;
    
    // Create a form for the POST request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/discussions/${discussionId}/summarize`;
    form.style.display = 'none';
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    document.body.appendChild(form);
    
    // Make AJAX request
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.value,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new FormData(form)
    })
    .then(response => {
        document.body.removeChild(form);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response:', text);
                throw new Error('Invalid response from server');
            }
        });
    })
    .then(data => {
        if (data.success) {
            summaryContent.innerHTML = `
                <div class="summary-text">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <small class="text-muted">
                            <i class="bx bx-check-circle me-1"></i>
                            AI Summary (${data.comment_count} comments analyzed)
                        </small>
                        <small class="text-muted">${new Date().toLocaleTimeString()}</small>
                    </div>
                    <div class="summary-content">${data.summary}</div>
                </div>
            `;
            showNotification('AI summary generated successfully!', 'success');
        } else {
            summaryContent.innerHTML = `
                <div class="error">
                    <i class="bx bx-error-circle me-1"></i>
                    ${data.error}
                </div>
            `;
            showNotification('Failed to generate summary: ' + data.error, 'error');
        }
    })
    .catch(error => {
        console.error('Error generating summary:', error);
        summaryContent.innerHTML = `
            <div class="error">
                <i class="bx bx-error-circle me-1"></i>
                Failed to generate summary. Please try again later.
            </div>
        `;
        showNotification('Failed to generate summary. Please try again later.', 'error');
    })
    .finally(() => {
        // Reset button state
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

function deleteComment(commentId, button) {
    // Show confirmation dialog
    if (!confirm('Are you sure you want to delete this comment? This action cannot be undone.')) {
        return;
    }

    const originalText = button.innerHTML;
    
    // Show loading state
    button.disabled = true;
    button.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Deleting...';
    
    // Create a form for the DELETE request
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/comments/${commentId}`;
    form.style.display = 'none';
    
    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    form.appendChild(csrfToken);
    
    // Add method override for DELETE
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    form.appendChild(methodField);
    
    document.body.appendChild(form);
    
    // Submit the form
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.value,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: new FormData(form)
    })
    .then(response => {
        document.body.removeChild(form);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Invalid JSON response:', text);
                throw new Error('Invalid response from server');
            }
        });
    })
    .then(data => {
        if (data.success) {
            // Find and remove the comment element
            const commentElement = document.querySelector(`[data-comment="${commentId}"]`);
            if (commentElement) {
                // Add fade out animation
                commentElement.style.transition = 'opacity 0.3s ease';
                commentElement.style.opacity = '0';
                
                setTimeout(() => {
                    commentElement.remove();
                }, 300);
            }
            showNotification('Comment deleted successfully!', 'success');
        } else {
            showNotification('Failed to delete comment: ' + (data.error || 'Unknown error'), 'error');
            // Reset button state
            button.disabled = false;
            button.innerHTML = originalText;
        }
    })
    .catch(error => {
        console.error('Error deleting comment:', error);
        showNotification('Failed to delete comment. Please try again later.', 'error');
        // Reset button state
        button.disabled = false;
        button.innerHTML = originalText;
    });
}

// Discussion Form Validation
document.addEventListener('DOMContentLoaded', function() {
    const discussionForm = document.getElementById('discussionForm');
    const titreInput = document.getElementById('titre');
    const contenuInput = document.getElementById('contenu');
    const titreCount = document.getElementById('titre_count');
    const contenuCount = document.getElementById('contenu_count');
    const submitBtn = document.getElementById('submitDiscussionBtn');
    
    if (discussionForm && titreInput && contenuInput) {
        // Character counters
        function updateCharacterCount(input, counter, maxLength) {
            const count = input.value.length;
            counter.textContent = count;
            
            if (count > maxLength * 0.9) {
                counter.classList.add('text-danger');
                counter.classList.remove('text-warning');
            } else if (count > maxLength * 0.8) {
                counter.classList.add('text-warning');
                counter.classList.remove('text-danger');
            } else {
                counter.classList.remove('text-warning', 'text-danger');
            }
        }
        
        // Real-time character counting
        titreInput.addEventListener('input', function() {
            updateCharacterCount(this, titreCount, 255);
            validateField(this);
        });
        
        contenuInput.addEventListener('input', function() {
            updateCharacterCount(this, contenuCount, 5000);
            validateField(this);
        });
        
        // Field validation
        function validateField(field) {
            const value = field.value.trim();
            const fieldName = field.name;
            let isValid = true;
            let errorMessage = '';
            
            // Remove existing validation classes
            field.classList.remove('is-valid', 'is-invalid');
            
            if (fieldName === 'titre') {
                if (!value) {
                    isValid = false;
                    errorMessage = 'Le titre de la discussion est obligatoire.';
                } else if (value.length < 5) {
                    isValid = false;
                    errorMessage = 'Le titre doit contenir au moins 5 caractères.';
                } else if (value.length > 255) {
                    isValid = false;
                    errorMessage = 'Le titre ne peut pas dépasser 255 caractères.';
                } else if (!/^[a-zA-Z0-9\s\-_.,!?()]+$/.test(value)) {
                    isValid = false;
                    errorMessage = 'Le titre contient des caractères non autorisés.';
                } else if (/(.)\1{4,}/.test(value)) {
                    isValid = false;
                    errorMessage = 'Le titre ne peut pas contenir de caractères répétés plus de 4 fois.';
                } else if (value.length > 10 && value === value.toUpperCase()) {
                    isValid = false;
                    errorMessage = 'Le titre ne peut pas être entièrement en majuscules.';
                }
            } else if (fieldName === 'contenu') {
                if (!value) {
                    isValid = false;
                    errorMessage = 'Le contenu de la discussion est obligatoire.';
                } else if (value.length < 10) {
                    isValid = false;
                    errorMessage = 'Le contenu doit contenir au moins 10 caractères.';
                } else if (value.length > 5000) {
                    isValid = false;
                    errorMessage = 'Le contenu ne peut pas dépasser 5000 caractères.';
                } else if (!/^[a-zA-Z0-9\s\-_.,!?()\n\r]+$/.test(value)) {
                    isValid = false;
                    errorMessage = 'Le contenu contient des caractères non autorisés.';
                } else if (/(.)\1{10,}/.test(value)) {
                    isValid = false;
                    errorMessage = 'Le contenu ne peut pas contenir de caractères répétés plus de 10 fois.';
                } else {
                    // Check for meaningful content
                    const meaningfulContent = value.replace(/[\s\p{P}]+/gu, '');
                    if (meaningfulContent.length < 5) {
                        isValid = false;
                        errorMessage = 'Le contenu doit contenir au moins 5 caractères significatifs.';
                    }
                }
            }
            
            // Apply validation classes and messages
            if (isValid) {
                field.classList.add('is-valid');
            } else {
                field.classList.add('is-invalid');
            }
            
            // Update error message
            const errorElement = document.getElementById(fieldName + '_error');
            if (errorElement) {
                errorElement.textContent = errorMessage;
            }
            
            return isValid;
        }
        
        // Form submission validation
        discussionForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            let isFormValid = true;
            
            // Validate all fields
            isFormValid &= validateField(titreInput);
            isFormValid &= validateField(contenuInput);
            
            if (isFormValid) {
                // Show loading state
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i> Creating...';
                submitBtn.disabled = true;
                
                // Submit form
                discussionForm.submit();
            } else {
                // Focus on first invalid field
                const firstInvalid = discussionForm.querySelector('.is-invalid');
                if (firstInvalid) {
                    firstInvalid.focus();
                }
                
                // Show error message
                showNotification('Please correct the errors before submitting.', 'error');
            }
        });
        
        // Real-time validation on blur
        titreInput.addEventListener('blur', function() {
            validateField(this);
        });
        
        contenuInput.addEventListener('blur', function() {
            validateField(this);
        });
        
        // Initialize character counts
        updateCharacterCount(titreInput, titreCount, 255);
        updateCharacterCount(contenuInput, contenuCount, 5000);
    }
    
    // Comment Form Validation
    function initializeCommentValidation() {
        // Handle comment form submissions
        document.addEventListener('submit', function(e) {
            const form = e.target.closest('.comment-form-ajax, .reply-form-ajax');
            if (!form) return;
            
            e.preventDefault();
            
            const textarea = form.querySelector('textarea[name="contenu"]');
            const discussionId = form.dataset.discussion;
            const parentId = form.dataset.parent;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (!textarea) return;
            
            // Validate comment content
            const value = textarea.value.trim();
            let isValid = true;
            let errorMessage = '';
            
            // Remove existing validation classes
            textarea.classList.remove('is-valid', 'is-invalid');
            
            if (!value) {
                isValid = false;
                errorMessage = 'Le contenu du commentaire est obligatoire.';
            } else if (value.length < 3) {
                isValid = false;
                errorMessage = 'Le commentaire doit contenir au moins 3 caractères.';
            } else if (value.length > 2000) {
                isValid = false;
                errorMessage = 'Le commentaire ne peut pas dépasser 2000 caractères.';
            } else if (!/^[a-zA-Z0-9\s\-_.,!?()\n\r]+$/.test(value)) {
                isValid = false;
                errorMessage = 'Le commentaire contient des caractères non autorisés.';
            } else if (/(.)\1{8,}/.test(value)) {
                isValid = false;
                errorMessage = 'Le commentaire ne peut pas contenir de caractères répétés plus de 8 fois.';
            } else {
                // Check for meaningful content
                const meaningfulContent = value.replace(/[\s\p{P}]+/gu, '');
                if (meaningfulContent.length < 2) {
                    isValid = false;
                    errorMessage = 'Le commentaire doit contenir au moins 2 caractères significatifs.';
                }
                
                // Check for excessive special characters
                const specialCharCount = (value.match(/[!@#$%^&*()_+=\[\]{}|;:,.<>?]/g) || []).length;
                if (specialCharCount > value.length * 0.3) {
                    isValid = false;
                    errorMessage = 'Le commentaire contient trop de caractères spéciaux.';
                }
            }
            
            if (!isValid) {
                textarea.classList.add('is-invalid');
                
                // Update error message
                const errorElement = document.getElementById(`comment_error_${discussionId}`);
                if (errorElement) {
                    errorElement.textContent = errorMessage;
                }
                
                // Focus on textarea
                textarea.focus();
                
                // Show error notification
                showNotification('Please correct the errors before submitting.', 'error');
                return;
            }
            
            // If valid, proceed with AJAX submission
            submitCommentForm(form, discussionId, parentId, submitBtn);
        });
        
        // Real-time character counting for comment textareas
        document.addEventListener('input', function(e) {
            if (e.target.matches('textarea[name="contenu"]')) {
                const textarea = e.target;
                const discussionId = textarea.closest('form').dataset.discussion;
                const counter = document.getElementById(`comment_count_${discussionId}`);
                
                if (counter) {
                    const count = textarea.value.length;
                    counter.textContent = count;
                    
                    if (count > 2000 * 0.9) {
                        counter.classList.add('text-danger');
                        counter.classList.remove('text-warning');
                    } else if (count > 2000 * 0.8) {
                        counter.classList.add('text-warning');
                        counter.classList.remove('text-danger');
                    } else {
                        counter.classList.remove('text-warning', 'text-danger');
                    }
                }
            }
        });
    }
    
    // Initialize comment validation
    initializeCommentValidation();
});
</script>