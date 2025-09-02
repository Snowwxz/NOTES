<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class NotesController extends Controller
{


    public function landing()
    {
        $publicNotes = Note::with('user')
            ->withCount('likes')
            ->where('is_public', true)
            ->latest()
            ->get();

        return view('landing', compact('publicNotes'));
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $user = User::find($userId);
        $notes = Note::where(function ($query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhereHas('collaborators', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                });
        })->with('user')->latest()->get();

        return view('notes', compact('notes', 'user'));
    }

    public function public()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $user = User::find($userId);
        $publicNotes = Note::with('user')
            ->withCount('likes')
            ->where('is_public', true)
            ->latest()
            ->get();

        return view('notes_public', compact('publicNotes', 'user'));
    }

    public function create()
    {
        return view('create');
    }


    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
        ]);

        $userId = Auth::id();
        $user = User::find($userId);
        $data = $request->only(['judul', 'deskripsi']);
        $data['is_public'] = $request->has('is_public');

        $note = $user->notes()->create($data);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notes berhasil ditambahkan!',
                'note' => $note
            ]);
        }

        return redirect()->route('notes.index')->with('success', 'Notes berhasil ditambahkan!');
    }


    public function show(string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $user = User::find($userId);
        $note = Note::with('user')->findOrFail($id);
        return view('show', compact('note', 'user'));
    }


    public function edit(string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $user = User::find($userId);
        $note = Note::findOrFail($id);

        if (!$note->canBeEditedBy($user)) {
            abort(403, 'Unauthorized action.');
        }
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'note' => $note
            ]);
        }

        return view('edit', compact('note'));
    }


    public function update(Request $request, string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'judul' => 'required',
            'deskripsi' => 'required',
        ]);

        $userId = Auth::id();
        $user = User::find($userId);
        $note = Note::findOrFail($id);

        if (!$note->canBeEditedBy($user)) {
            abort(403, 'Unauthorized action.');
        }
        $data = $request->only(['judul', 'deskripsi']);
        $data['is_public'] = $request->has('is_public');

        $note->update($data);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Notes berhasil diperbarui!',
                'note' => $note
            ]);
        }

        return redirect()
            ->route('notes.index')
            ->with('success', 'Notes berhasil diperbarui!');
    }


    public function destroy(string $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $userId = Auth::id();
        $user = User::find($userId);
        $note = $user->notes()->findOrFail($id);
        $note->delete();
        return redirect()->route('notes.index')->with('success', 'Notes berhasil dihapus');
    }
}
