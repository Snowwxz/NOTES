<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LikeController extends Controller
{
    public function toggle(Request $request, Note $note): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['error' => 'User harus login'], 401);
        }

        $existingLike = Like::where('user_id', $user->id)
                           ->where('note_id', $note->id)
                           ->first();

        if ($existingLike) {
            $existingLike->delete();
            $isLiked = false;
            $message = 'Note di-unlike';
        } else {
            Like::create([
                'user_id' => $user->id,
                'note_id' => $note->id
            ]);
            $isLiked = true;
            $message = 'Note di-like';
        }

        $likesCount = $note->likes()->count();

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_liked' => $isLiked,
            'likes_count' => $likesCount
        ]);
    }

    public function check(Note $note): JsonResponse
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json(['is_liked' => false, 'likes_count' => $note->likes()->count()]);
        }

        $isLiked = $note->isLikedBy($user);
        $likesCount = $note->likes()->count();

        return response()->json([
            'is_liked' => $isLiked,
            'likes_count' => $likesCount
        ]);
    }
}
