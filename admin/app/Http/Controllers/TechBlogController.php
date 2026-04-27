<?php

namespace App\Http\Controllers;

use App\Models\TechBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TechBlogController extends Controller
{
    public function index()
    {
        $techBlogs = TechBlog::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('admin.tech_blogs.index', compact('techBlogs'));
    }

    public function create()
    {
        return view('admin.tech_blogs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:500',
            'category'       => 'nullable|string|max:100',
            'description'    => 'nullable|string',
            'title_en'       => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:500',
            'description_jp' => 'nullable|string',
            'published_date' => 'nullable|date',
            'sort_order'     => 'nullable|integer',
            'is_active'      => 'nullable|boolean',
            'image'          => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/tech_blogs');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/tech_blogs/' . $filename;
        }

        $data['sort_order']     = $data['sort_order'] ?? 0;
        $data['is_active']      = $request->has('is_active') ? 1 : 0;
        // 날짜: 입력값 우선, 없으면 오늘
        $data['published_date'] = $request->filled('published_date')
            ? $request->input('published_date')
            : now()->toDateString();

        unset($data['image']);
        TechBlog::create($data);

        return redirect()->route('tech-blogs.index')->with('success', '테크블로그가 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $techBlog = TechBlog::findOrFail($id);
        return view('admin.tech_blogs.edit', compact('techBlog'));
    }

    public function update(Request $request, string $id)
    {
        $techBlog = TechBlog::findOrFail($id);

        $data = $request->validate([
            'title'          => 'required|string|max:500',
            'category'       => 'nullable|string|max:100',
            'description'    => 'nullable|string',
            'title_en'       => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:500',
            'description_jp' => 'nullable|string',
            'published_date' => 'nullable|date',
            'sort_order'     => 'nullable|integer',
            'is_active'      => 'nullable|boolean',
            'image'          => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            if ($techBlog->image_path) {
                $oldPath = public_path($techBlog->image_path);
                if (file_exists($oldPath)) @unlink($oldPath);
            }
            $uploadDir = public_path('uploads/tech_blogs');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/tech_blogs/' . $filename;
        }

        $data['sort_order']     = $data['sort_order'] ?? $techBlog->sort_order;
        $data['is_active']      = $request->has('is_active') ? 1 : 0;
        // 날짜: 입력값 우선, 없으면 기존 날짜 유지 (그것도 없으면 오늘)
        $data['published_date'] = $request->filled('published_date')
            ? $request->input('published_date')
            : ($techBlog->published_date ?? now()->toDateString());

        unset($data['image']);
        $techBlog->update($data);

        return redirect()->route('tech-blogs.index')->with('success', '테크블로그가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $techBlog = TechBlog::findOrFail($id);
        if ($techBlog->image_path) {
            $oldPath = public_path($techBlog->image_path);
            if (file_exists($oldPath)) @unlink($oldPath);
        }
        $techBlog->delete();
        return redirect()->route('tech-blogs.index')->with('success', '테크블로그가 삭제되었습니다.');
    }

    // ── 에디터 이미지 업로드 ──
    public function uploadEditorImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:10240']);

        $uploadDir = public_path('uploads/tech_blogs/editor');
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
        $request->file('image')->move($uploadDir, $filename);

        return response()->json([
            'url' => '/admin/uploads/tech_blogs/editor/' . $filename,
        ])->header('Access-Control-Allow-Origin', '*');
    }

    // ── Public API  GET /admin/api/tech-blogs ──
    public function apiIndex(Request $request)
    {
        $query = TechBlog::where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->get()->map(fn($b) => [
            'id'             => $b->id,
            'title'          => $b->title,
            'category'       => $b->category,
            'description'    => $b->description,
            'thumbnail'      => $b->image_url,
            'published_date' => $b->published_date,
        ]);

        return response()->json($items)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
