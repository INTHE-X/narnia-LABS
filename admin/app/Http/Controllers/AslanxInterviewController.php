<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\AslanxInterview;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AslanxInterviewController extends Controller
{
    /* ── 어드민 목록 ── */
    public function index()
    {
        $interviews = AslanxInterview::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.aslanx.interview.index', compact('interviews'));
    }

    /* ── 등록 폼 ── */
    public function create()
    {
        return view('admin.aslanx.interview.create');
    }

    /* ── 등록 처리 ── */
    public function store(Request $request)
    {
        $data = $request->validate([
            'company'      => 'required|string|max:100',
            'company_en'   => 'nullable|string|max:100',
            'company_jp'   => 'nullable|string|max:100',
            'position'     => 'nullable|string|max:100',
            'position_en'  => 'nullable|string|max:100',
            'position_jp'  => 'nullable|string|max:100',
            'content'      => 'required|string',
            'content_en'   => 'nullable|string',
            'content_jp'   => 'nullable|string',
            'logo'         => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,svg|max:2048',
            'sort_order'   => 'nullable|integer',
        ]);

        $data['is_visible'] = $request->has('is_visible') ? 1 : 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->uploadLogo($request->file('logo'));
        }
        unset($data['logo']);

        AslanxInterview::create($data);

        return redirect()->route('aslanx-interviews.index')
            ->with('success', '인터뷰가 등록되었습니다.');
    }

    /* ── 수정 폼 ── */
    public function edit(string $id)
    {
        $interview = AslanxInterview::findOrFail($id);
        return view('admin.aslanx.interview.edit', compact('interview'));
    }

    /* ── 수정 처리 ── */
    public function update(Request $request, string $id)
    {
        $interview = AslanxInterview::findOrFail($id);

        $data = $request->validate([
            'company'      => 'required|string|max:100',
            'company_en'   => 'nullable|string|max:100',
            'company_jp'   => 'nullable|string|max:100',
            'position'     => 'nullable|string|max:100',
            'position_en'  => 'nullable|string|max:100',
            'position_jp'  => 'nullable|string|max:100',
            'content'      => 'required|string',
            'content_en'   => 'nullable|string',
            'content_jp'   => 'nullable|string',
            'logo'         => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,svg|max:2048',
            'sort_order'   => 'nullable|integer',
        ]);

        $data['is_visible'] = $request->has('is_visible') ? 1 : 0;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->uploadLogo($request->file('logo'));
        }
        unset($data['logo']);

        $interview->update($data);

        return redirect()->route('aslanx-interviews.index')
            ->with('success', '인터뷰가 수정되었습니다.');
    }

    /* ── 삭제 ── */
    public function destroy(string $id)
    {
        AslanxInterview::findOrFail($id)->delete();
        return redirect()->route('aslanx-interviews.index')
            ->with('success', '인터뷰가 삭제되었습니다.');
    }

    /* ── 공개 API ── */
    public function apiIndex()
    {
        $items = AslanxInterview::where('is_visible', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn($i) => [
                'id'          => $i->id,
                'company'     => $i->company,
                'company_en'  => $i->company_en,
                'company_jp'  => $i->company_jp,
                'position'    => $i->position,
                'position_en' => $i->position_en,
                'position_jp' => $i->position_jp,
                'content'     => $i->content,
                'content_en'  => $i->content_en,
                'content_jp'  => $i->content_jp,
                'logo_url'    => $i->logo_path ? '/uploads/aslanx_logos/' . basename($i->logo_path) : null,
            ]);

        return response()->json($items);
    }

    /* ── 로고 업로드 헬퍼 ── */
    private function uploadLogo($file): string
    {
        $dir = dirname(base_path()) . '/uploads/aslanx_logos';
        $path = ImageHelper::upload($file, $dir, 85);
        // 웹 접근 경로로 변환 (www 루트 기준)
        return 'uploads/aslanx_logos/' . basename($path);
    }
}
