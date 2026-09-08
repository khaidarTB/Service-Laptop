<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceComment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Service $service)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'is_internal' => 'nullable|boolean',
        ]);

        $isInternal = auth()->user()->isCustomer() ? false : ($request->boolean('is_internal', false));

        ServiceComment::create([
            'service_id' => $service->id,
            'user_id' => auth()->id(),
            'message' => $request->message,
            'is_internal' => $isInternal,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(Service $service, ServiceComment $comment)
    {
        if ($comment->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Anda tidak bisa menghapus komentar ini.');
        }

        $comment->delete();

        return redirect()->back()->with('success', 'Komentar berhasil dihapus!');
    }
}
