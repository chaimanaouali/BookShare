<?php

namespace App\Http\Controllers;

use App\Models\Discussion;
use App\Models\Comment;
use App\Models\CommentVote;
use App\Models\BibliothequeVirtuelle;
use App\Services\AiSummaryService;
use App\Http\Requests\StoreDiscussionRequest;
use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiscussionController extends Controller
{
    /**
     * Store a new discussion for a bibliotheque.
     */
    public function store(StoreDiscussionRequest $request, $bibliothequeId)
    {
        $bibliotheque = BibliothequeVirtuelle::findOrFail($bibliothequeId);

        $discussion = Discussion::create([
            'titre' => $request->titre,
            'contenu' => $request->contenu,
            'user_id' => Auth::id(),
            'bibliotheque_id' => $bibliotheque->id,
            'est_resolu' => false,
        ]);

        return redirect()->back()->with('success', 'Discussion created successfully!');
    }

    /**
     * Store a new comment for a discussion.
     */
    public function storeComment(StoreCommentRequest $request, $discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);

        $comment = Comment::create([
            'contenu' => $request->contenu,
            'user_id' => Auth::id(),
            'discussion_id' => $discussion->id,
            'parent_id' => $request->parent_id,
            'upvotes' => 0,
            'downvotes' => 0,
            'score' => 0,
        ]);

        // Load the comment with user relationship for AJAX response
        $comment->load('user');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'message' => 'Comment added successfully!'
            ]);
        }

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Mark a discussion as resolved.
     */
    public function markResolved($discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);
        
        // Only the discussion creator or bibliotheque owner can mark as resolved
        if (Auth::id() === $discussion->user_id || Auth::id() === $discussion->bibliotheque->user_id) {
            $discussion->update(['est_resolu' => true]);
            return redirect()->back()->with('success', 'Discussion marked as resolved!');
        }

        return redirect()->back()->with('error', 'You are not authorized to perform this action.');
    }

    /**
     * Mark a discussion as unresolved.
     */
    public function markUnresolved($discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);
        
        // Only the discussion creator or bibliotheque owner can mark as unresolved
        if (Auth::id() === $discussion->user_id || Auth::id() === $discussion->bibliotheque->user_id) {
            $discussion->update(['est_resolu' => false]);
            return redirect()->back()->with('success', 'Discussion marked as unresolved!');
        }

        return redirect()->back()->with('error', 'You are not authorized to perform this action.');
    }

    /**
     * Vote on a comment (upvote or downvote).
     */
    public function voteComment(Request $request, $commentId)
    {
        $request->validate([
            'vote_type' => 'required|in:upvote,downvote,remove'
        ]);

        $comment = Comment::findOrFail($commentId);
        $userId = Auth::id();

        // Check if user already voted
        $existingVote = CommentVote::where('user_id', $userId)
            ->where('comment_id', $commentId)
            ->first();

        if ($request->vote_type === 'remove') {
            // Remove existing vote
            if ($existingVote) {
                $this->updateCommentScore($comment, $existingVote->vote_type, 'remove');
                $existingVote->delete();
            }
        } else {
            if ($existingVote) {
                // Change existing vote
                if ($existingVote->vote_type !== $request->vote_type) {
                    $this->updateCommentScore($comment, $existingVote->vote_type, 'remove');
                    $this->updateCommentScore($comment, $request->vote_type, 'add');
                    $existingVote->update(['vote_type' => $request->vote_type]);
                }
            } else {
                // Create new vote
                CommentVote::create([
                    'user_id' => $userId,
                    'comment_id' => $commentId,
                    'vote_type' => $request->vote_type,
                ]);
                $this->updateCommentScore($comment, $request->vote_type, 'add');
            }
        }

        // Refresh the comment to get updated scores
        $comment->refresh();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comment' => $comment,
                'user_vote' => $comment->userVote($userId)
            ]);
        }

        return redirect()->back();
    }

    /**
     * Update comment score based on vote changes.
     */
    private function updateCommentScore($comment, $voteType, $action)
    {
        if ($voteType === 'upvote') {
            $comment->upvotes += ($action === 'add') ? 1 : -1;
        } else {
            $comment->downvotes += ($action === 'add') ? 1 : -1;
        }
        
        $comment->score = $comment->upvotes - $comment->downvotes;
        $comment->save();
    }

    /**
     * Get comments for a discussion with sorting.
     */
    public function getComments(Request $request, $discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);
        $sortBy = $request->get('sort', 'newest');

        $query = $discussion->topLevelComments()->with(['user', 'replies.user', 'votes']);

        switch ($sortBy) {
            case 'score':
                $query->byScore();
                break;
            case 'most_active':
                $query->byMostActive();
                break;
            case 'newest':
            default:
                $query->byNewest();
                break;
        }

        $comments = $query->get();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);
        }

        return $comments;
    }

    /**
     * Generate AI summary for a discussion.
     */
    public function generateSummary(Request $request, $discussionId)
    {
        $discussion = Discussion::findOrFail($discussionId);
        $aiService = new AiSummaryService();

        // Validate API configuration
        if (!$aiService->validateConfiguration()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'AI service is not properly configured. Please contact the administrator.'
                ], 500);
            }
            return redirect()->back()->with('error', 'AI service is not properly configured.');
        }

        // Generate summary
        $result = $aiService->summarizeDiscussion($discussion);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json($result);
        }

        if ($result['success']) {
            return redirect()->back()->with('ai_summary', $result['summary']);
        } else {
            return redirect()->back()->with('error', $result['error']);
        }
    }

    /**
     * Delete a comment (only by the comment author).
     */
    public function deleteComment(Request $request, $commentId)
    {
        try {
            // Log the request for debugging
            \Log::info('Delete comment request', [
                'commentId' => $commentId,
                'userId' => Auth::id(),
                'isAjax' => $request->ajax(),
                'wantsJson' => $request->wantsJson(),
                'headers' => $request->headers->all()
            ]);
            
            $comment = Comment::findOrFail($commentId);
            
            // Check if user is the author of the comment
            if (Auth::id() !== $comment->user_id) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'You are not authorized to delete this comment.'
                    ], 403);
                }
                return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
            }

            // Delete all votes for this comment first
            CommentVote::where('comment_id', $commentId)->delete();
            
            // Delete all replies to this comment
            Comment::where('parent_id', $commentId)->delete();
            
            // Delete the comment itself
            $comment->delete();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Comment deleted successfully!'
                ]);
            }

            return redirect()->back()->with('success', 'Comment deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Error deleting comment: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'error' => 'An error occurred while deleting the comment.'
                ], 500);
            }
            
            return redirect()->back()->with('error', 'An error occurred while deleting the comment.');
        }
    }
}