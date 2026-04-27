<?php

namespace App\Http\Controllers;

use App\Models\CaseStudy;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaseStudyController extends Controller
{
    public function index()
    {
        $caseStudies = CaseStudy::orderBy('sort_order')->orderByDesc('created_at')->get();
        return view('admin.case_studies.index', compact('caseStudies'));
    }

    public function create()
    {
        return view('admin.case_studies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'tags'         => 'nullable|string|max:500',
            'description'  => 'nullable|string',
            'title_en'       => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:500',
            'description_jp' => 'nullable|string',
            'image'        => 'nullable|image|max:5120',
            'link'         => 'nullable|url|max:500',
            'is_featured'  => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/case_studies');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/case_studies/' . $filename;
        }

        $data['is_featured']  = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order']   = $data['sort_order'] ?? 0;

        CaseStudy::create($data);

        return redirect()->route('case-studies.index')->with('success', '케이스스터디가 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $caseStudy = CaseStudy::findOrFail($id);
        return view('admin.case_studies.edit', compact('caseStudy'));
    }

    public function update(Request $request, string $id)
    {
        $caseStudy = CaseStudy::findOrFail($id);

        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'tags'         => 'nullable|string|max:500',
            'description'  => 'nullable|string',
            'title_en'       => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:500',
            'description_jp' => 'nullable|string',
            'image'        => 'nullable|image|max:5120',
            'link'         => 'nullable|url|max:500',
            'is_featured'  => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
            'sort_order'   => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($caseStudy->image_path) {
                $oldPath = public_path($caseStudy->image_path);
                if (file_exists($oldPath)) @unlink($oldPath);
            }
            $uploadDir = public_path('uploads/case_studies');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/case_studies/' . $filename;
        }

        $data['is_featured']  = $request->boolean('is_featured');
        $data['is_published'] = $request->boolean('is_published');
        $data['sort_order']   = $data['sort_order'] ?? $caseStudy->sort_order;

        $caseStudy->update($data);

        return redirect()->route('case-studies.index')->with('success', '케이스스터디가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $caseStudy = CaseStudy::findOrFail($id);
        if ($caseStudy->image_path) {
            $oldPath = public_path($caseStudy->image_path);
            if (file_exists($oldPath)) @unlink($oldPath);
        }
        $caseStudy->delete();
        return redirect()->route('case-studies.index')->with('success', '케이스스터디가 삭제되었습니다.');
    }

    public function toggleFeatured(string $id)
    {
        $caseStudy = CaseStudy::findOrFail($id);
        $caseStudy->update(['is_featured' => !$caseStudy->is_featured]);
        return response()->json(['is_featured' => $caseStudy->is_featured]);
    }

    // ── Public API  GET /admin/api/case-studies ──
    public function apiIndex(Request $request)
    {
        $query = CaseStudy::where('is_published', true)
            ->orderBy('sort_order')->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->get()->map(function ($c) {
            return [
                'id'             => $c->id,
                'title'          => $c->title,
                'title_en'       => $c->title_en,
                'title_jp'       => $c->title_jp,
                'category'       => $c->category,
                'tags'           => $c->tags_array,
                'description'    => $c->description,
                'description_en' => $c->description_en,
                'description_jp' => $c->description_jp,
                'image_url'      => $c->image_url,
                'link'           => $c->link,
                'is_featured'    => $c->is_featured,
            ];
        });

        return response()->json($items)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
