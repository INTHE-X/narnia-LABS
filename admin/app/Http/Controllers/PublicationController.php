<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;

class PublicationController extends Controller
{
    public function index()
    {
        $publications = Publication::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('admin.publications.index', compact('publications'));
    }

    public function create()
    {
        return view('admin.publications.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:500',
            'author'      => 'nullable|string|max:300',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'title_en'       => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:500',
            'description_jp' => 'nullable|string',
            'source'      => 'nullable|string|max:500',
            'link'        => 'nullable|url|max:500',
            'sort_order'  => 'nullable|integer',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        Publication::create($data);

        return redirect()->route('publications.index')->with('success', '퍼블리케이션이 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $publication = Publication::findOrFail($id);
        return view('admin.publications.edit', compact('publication'));
    }

    public function update(Request $request, string $id)
    {
        $publication = Publication::findOrFail($id);

        $data = $request->validate([
            'title'       => 'required|string|max:500',
            'author'      => 'nullable|string|max:300',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'title_en'       => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:500',
            'description_jp' => 'nullable|string',
            'source'      => 'nullable|string|max:500',
            'link'        => 'nullable|url|max:500',
            'sort_order'  => 'nullable|integer',
        ]);

        $data['sort_order'] = $data['sort_order'] ?? $publication->sort_order;
        $publication->update($data);

        return redirect()->route('publications.index')->with('success', '퍼블리케이션이 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        Publication::findOrFail($id)->delete();
        return redirect()->route('publications.index')->with('success', '퍼블리케이션이 삭제되었습니다.');
    }

    // ── Public API  GET /admin/api/publications ──
    public function apiIndex(Request $request)
    {
        $query = Publication::orderBy('sort_order')->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->get()->map(fn($p) => [
            'id'          => $p->id,
            'title'       => $p->title,
            'title_en'    => $p->title_en,
            'title_jp'    => $p->title_jp,
            'author'      => $p->author,
            'category'    => $p->category,
            'description' => $p->description,
            'description_en' => $p->description_en,
            'description_jp' => $p->description_jp,
            'source'      => $p->source,
            'link'        => $p->link,
        ]);

        return response()->json($items)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
