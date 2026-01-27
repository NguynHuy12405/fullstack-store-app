<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyReviewRequest;
use App\Models\Review;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use AuthorizesRequests;

    public function reply(ReplyReviewRequest $request, Review $review)
    {
        $this->authorize('reply', $review);

        if ($review->reply) {
            return back()->with('error', 'Đánh giá này đã được phản hồi.');
        }

        $review->update([
            'reply'    => $request->reply,
            'replier'  => Auth::id(),
            'reply_at' => now(),
        ]);

        return back()->with('success', 'Đã phản hồi đánh giá.');
    }
}
