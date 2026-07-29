<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('tasks')->orderBy('nama_tag')->paginate(10);

        return view('tags.index', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_tag' => ['required', 'string', 'max:255', 'unique:tags,nama_tag'],
            'warna' => ['nullable', 'string', 'max:20'],
        ]);
        $validated['warna'] = $validated['warna'] ?? 'astra';

        Tag::create($validated);

        return back()->with('success', 'Tag berhasil ditambahkan.');
    }

    public function update(Request $request, Tag $tag)
    {
        $validated = $request->validate([
            'nama_tag' => ['required', 'string', 'max:255', 'unique:tags,nama_tag,'.$tag->id],
        ]);

        $tag->update($validated);

        return back()->with('success', 'Tag berhasil diperbarui.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();

        return back()->with('success', 'Tag berhasil dihapus.');
    }
}
