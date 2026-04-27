<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('sort_order')->orderByDesc('published_at')->get();
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:500',
            'source'       => 'nullable|string|max:300',
            'category'     => 'nullable|string|max:100',
            'content'      => 'nullable|string',
            'title_en'    => 'nullable|string|max:500',
            'content_en'  => 'nullable|string',
            'title_jp'    => 'nullable|string|max:500',
            'content_jp'  => 'nullable|string',
            'image'        => 'nullable|image|max:5120',
            'link'         => 'nullable|url|max:500',
            'is_featured'  => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'sort_order'   => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/news');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/news/' . $filename;
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['category']    = $data['category'] ?? 'news';
        $data['sort_order']  = $data['sort_order'] ?? 0;

        News::create($data);
        return redirect()->route('news.index')->with('success', '뉴스가 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $newsItem = News::findOrFail($id);
        return view('admin.news.edit', compact('newsItem'));
    }

    public function update(Request $request, string $id)
    {
        $newsItem = News::findOrFail($id);

        $data = $request->validate([
            'title'        => 'required|string|max:500',
            'source'       => 'nullable|string|max:300',
            'category'     => 'nullable|string|max:100',
            'content'      => 'nullable|string',
            'title_en'    => 'nullable|string|max:500',
            'content_en'  => 'nullable|string',
            'title_jp'    => 'nullable|string|max:500',
            'content_jp'  => 'nullable|string',
            'image'        => 'nullable|image|max:5120',
            'link'         => 'nullable|url|max:500',
            'is_featured'  => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'sort_order'   => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($newsItem->image_path) {
                $old = public_path($newsItem->image_path);
                if (file_exists($old)) @unlink($old);
            }
            $uploadDir = public_path('uploads/news');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/news/' . $filename;
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['category']    = $data['category'] ?? $newsItem->category;
        $data['sort_order']  = $data['sort_order'] ?? $newsItem->sort_order;

        $newsItem->update($data);
        return redirect()->route('news.index')->with('success', '뉴스가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $newsItem = News::findOrFail($id);
        if ($newsItem->image_path) {
            $old = public_path($newsItem->image_path);
            if (file_exists($old)) @unlink($old);
        }
        $newsItem->delete();
        return redirect()->route('news.index')->with('success', '뉴스가 삭제되었습니다.');
    }

    public function toggleFeatured(string $id)
    {
        $newsItem = News::findOrFail($id);
        $newsItem->update(['is_featured' => !$newsItem->is_featured]);
        return response()->json(['is_featured' => $newsItem->is_featured]);
    }

    // ── Public API  GET /admin/api/news-latest ──
    public function apiLatest()
    {
        $items = News::orderBy('sort_order')
            ->orderByDesc('published_at')
            ->limit(9)
            ->get()
            ->map(fn($n) => [
                'id'          => $n->id,
                'title'       => $n->title,
                'title_en'    => $n->title_en,
                'title_jp'    => $n->title_jp,
                'source'      => $n->source,
                'category'    => $n->category,
                'image_url'   => $n->image_url,
                'link'        => $n->link,
                'is_featured' => $n->is_featured,
                'date'        => $n->formatted_date,
                'year'        => $n->year,
            ]);

        return response()->json($items)
            ->header('Access-Control-Allow-Origin', '*');
    }

    // ── Public API  GET /admin/api/news ──
    public function apiIndex(Request $request)
    {
        $query = News::orderBy('sort_order')->orderByDesc('published_at');

        if ($request->filled('year')) {
            $query->whereYear('published_at', $request->year);
        }
        if ($request->filled('featured')) {
            $query->where('is_featured', true);
        }

        $items = $query->get()->map(fn($n) => [
            'id'          => $n->id,
            'title'       => $n->title,
            'title_en'    => $n->title_en,
            'title_jp'    => $n->title_jp,
            'source'      => $n->source,
            'category'    => $n->category,
            'content'     => $n->content,
            'content_en'  => $n->content_en,
            'content_jp'  => $n->content_jp,
            'image_url'   => $n->image_url,
            'link'        => $n->link,
            'is_featured' => $n->is_featured,
            'date'        => $n->formatted_date,
            'year'        => $n->year,
        ]);

        return response()->json($items)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
