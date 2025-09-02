<?php

namespace App\Http\Controllers;

use App\Models\Collab;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CollabController extends Controller
{

    public function addCollaborator(Request $request): JsonResponse
    {
        $request->validate([
            'note_id' => 'required|exists:notes,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $note = Note::findOrFail($request->note_id);
        $user = User::findOrFail($request->user_id);
        $currentUser = auth()->user();

        if ($note->user_id !== $currentUser->id && !$note->isCollaborator($currentUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk menambahkan kolaborator ke note ini'
            ], 403);
        }
        if ($note->isCollaborator($user)) {
            return response()->json([
                'success' => false,
                'message' => 'User sudah menjadi kolaborator'
            ], 400);
        }


        $note->collaborators()->attach($user->id);

        return response()->json([
            'success' => true,
            'message' => 'Kolaborator berhasil ditambahkan',
            'collaborator' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }


    public function removeCollaborator(Request $request): JsonResponse
    {
        $request->validate([
            'note_id' => 'required|exists:notes,id',
            'user_id' => 'required|exists:users,id'
        ]);

        $note = Note::findOrFail($request->note_id);
        $currentUser = auth()->user();



       


        try {

            $collaboration = \DB::table('collab')
                ->where('note_id', $request->note_id)
                ->where('user_id', $request->user_id)
                ->first();
                
            if (!$collaboration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kolaborasi tidak ditemukan'
                ], 404);
            }
            

            $deleted = \DB::table('collab')
                ->where('note_id', $request->note_id)
                ->where('user_id', $request->user_id)
                ->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Kolaborator berhasil dihapus',
                'deleted_count' => $deleted
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saat menghapus kolaborator'
            ], 500);
        }
    }


    public function getCollaborators(Request $request): JsonResponse
    {
        $request->validate([
            'note_id' => 'required|exists:notes,id'
        ]);

        $note = Note::with('collaborators')->findOrFail($request->note_id);
        $currentUser = auth()->user();

        if ($note->user_id !== $currentUser->id && !$note->isCollaborator($currentUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk melihat kolaborator note ini'
            ], 403);
        }
        
        return response()->json([
            'success' => true,
            'collaborators' => $note->collaborators->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
            })
        ]);
    }


    public function getAvailableUsers(Request $request): JsonResponse
    {
        $request->validate([
            'note_id' => 'required|exists:notes,id'
        ]);

        $note = Note::findOrFail($request->note_id);
        $currentUser = auth()->user();

        if ($note->user_id !== $currentUser->id && !$note->isCollaborator($currentUser)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki izin untuk melihat daftar user yang tersedia'
            ], 403);
        }


        $availableUsers = User::where('id', '!=', $currentUser->id)
            ->whereNotIn('id', $note->collaborators->pluck('id'))
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'users' => $availableUsers
        ]);
    }
}
