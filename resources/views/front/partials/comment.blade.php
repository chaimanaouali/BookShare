<div class="comment-card" data-comment="{{ $comment->id }}">
    <div class="comment-header">
        <div class="comment-meta">
            <strong>{{ $comment->user->name }}</strong> • {{ $comment->created_at->diffForHumans() }}
        </div>
    </div>
    <div class="comment-body">
        <div class="comment-content">{{ $comment->contenu }}</div>
        <div class="vote-section">
            <button class="vote-btn upvote {{ $comment->userVote(Auth::id()) && $comment->userVote(Auth::id())->vote_type === 'upvote' ? 'active' : '' }}" 
                    data-comment="{{ $comment->id }}" 
                    data-vote="upvote" 
                    data-discussion="{{ $discussion->id }}">
                <i class="bx bx-up-arrow-alt"></i>
            </button>
            <span class="vote-score">{{ $comment->score ?? 0 }}</span>
            <button class="vote-btn downvote {{ $comment->userVote(Auth::id()) && $comment->userVote(Auth::id())->vote_type === 'downvote' ? 'active' : '' }}" 
                    data-comment="{{ $comment->id }}" 
                    data-vote="downvote" 
                    data-discussion="{{ $discussion->id }}">
                <i class="bx bx-down-arrow-alt"></i>
            </button>
            @if(Auth::user()->role !== 'admin')
              <button class="btn btn-link btn-sm p-0 text-primary ms-3" onclick="toggleReplyForm({{ $comment->id }})">
                  <i class="bx bx-reply"></i> Reply
              </button>
            @endif
            @if(Auth::id() === $comment->user_id)
              <button class="btn btn-link btn-sm p-0 text-danger ms-2 delete-comment-btn" data-comment="{{ $comment->id }}">
                  <i class="bx bx-trash"></i> Delete
              </button>
            @endif
        </div>
        @if(Auth::user()->role !== 'admin')
          <div id="reply-form-{{ $comment->id }}" class="reply-form">
              <form class="reply-form-ajax" data-discussion="{{ $discussion->id }}" data-parent="{{ $comment->id }}">
                  @csrf
                  <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                  <div class="d-flex gap-2">
                      <textarea name="contenu" class="form-control form-control-sm" rows="2" placeholder="Write a reply..." required></textarea>
                      <button type="submit" class="btn btn-primary btn-sm">
                          <i class="bx bx-send"></i>
                      </button>
                  </div>
              </form>
          </div>
        @endif
        
        <!-- Replies -->
        @foreach($comment->replies as $reply)
            <div class="reply-section">
                <div class="comment-card">
                    <div class="comment-header">
                        <div class="comment-meta">
                            <strong>{{ $reply->user->name }}</strong> • {{ $reply->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="comment-body">
                        <div class="comment-content">{{ $reply->contenu }}</div>
                        <div class="vote-section">
                            <button class="vote-btn upvote {{ $reply->userVote(Auth::id()) && $reply->userVote(Auth::id())->vote_type === 'upvote' ? 'active' : '' }}" 
                                    data-comment="{{ $reply->id }}" 
                                    data-vote="upvote" 
                                    data-discussion="{{ $discussion->id }}">
                                <i class="bx bx-up-arrow-alt"></i>
                            </button>
                            <span class="vote-score">{{ $reply->score ?? 0 }}</span>
                            <button class="vote-btn downvote {{ $reply->userVote(Auth::id()) && $reply->userVote(Auth::id())->vote_type === 'downvote' ? 'active' : '' }}" 
                                    data-comment="{{ $reply->id }}" 
                                    data-vote="downvote" 
                                    data-discussion="{{ $discussion->id }}">
                                <i class="bx bx-down-arrow-alt"></i>
                            </button>
                            @if(Auth::id() === $reply->user_id)
                              <button class="btn btn-link btn-sm p-0 text-danger ms-2 delete-comment-btn" data-comment="{{ $reply->id }}">
                                  <i class="bx bx-trash"></i> Delete
                              </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
