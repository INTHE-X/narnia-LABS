<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\EducationApplicant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Education::orderBy('sort_order')->orderByDesc('published_at')->get();
        return view('admin.education.index', compact('educations'));
    }

    public function create()
    {
        return view('admin.education.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required|string|max:255',
            'category'     => 'required|string|max:100',
            'description'  => 'nullable|string',
            'title_en'       => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:255',
            'description_jp' => 'nullable|string',
            'image'        => 'nullable|image|max:5120',
            'pdfs.*'       => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,hwp,zip|max:20480',
            'link'         => 'nullable|url|max:500',
            'published_at' => 'nullable|date',
            'sort_order'   => 'nullable|integer',
        ]);

        // 대표 이미지 업로드
        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/educations');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/educations/' . $filename;
        }

        // PDF / 첨부파일 복수 업로드
        $pdfPaths = [];
        if ($request->hasFile('pdfs')) {
            $uploadDir = public_path('uploads/education_pdfs');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            foreach ($request->file('pdfs') as $file) {
                $safeName = Str::uuid() . '__' . $file->getClientOriginalName();
                $file->move($uploadDir, $safeName);
                $pdfPaths[] = 'uploads/education_pdfs/' . $safeName;
            }
        }
        $data['pdf_paths'] = count($pdfPaths) ? $pdfPaths : null;

        $data['sort_order'] = $data['sort_order'] ?? 0;

        Education::create($data);

        return redirect()->route('education.index')->with('success', '교육 콘텐츠가 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $education = Education::findOrFail($id);
        return view('admin.education.edit', compact('education'));
    }

    public function update(Request $request, string $id)
    {
        $education = Education::findOrFail($id);

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'category'       => 'required|string|max:100',
            'description'    => 'nullable|string',
            'title_en'       => 'nullable|string|max:255',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:255',
            'description_jp' => 'nullable|string',
            'image'          => 'nullable|image|max:5120',
            'pdfs.*'         => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,hwp,zip|max:20480',
            'remove_pdfs.*'  => 'nullable|string',
            'link'           => 'nullable|url|max:500',
            'published_at'   => 'nullable|date',
            'sort_order'     => 'nullable|integer',
        ]);

        // 대표 이미지 교체
        if ($request->hasFile('image')) {
            if ($education->image_path) {
                $old = public_path($education->image_path);
                if (file_exists($old)) @unlink($old);
            }
            $uploadDir = public_path('uploads/educations');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/educations/' . $filename;
        }

        // 기존 PDF 중 삭제 요청된 것 처리
        $existing = $education->pdf_paths ?? [];
        $toRemove = $request->input('remove_pdfs', []);
        foreach ($toRemove as $removePath) {
            $fullPath = public_path($removePath);
            if (file_exists($fullPath)) @unlink($fullPath);
            $existing = array_filter($existing, fn($p) => $p !== $removePath);
        }
        $existing = array_values($existing);

        // 새 PDF 추가 업로드
        if ($request->hasFile('pdfs')) {
            $uploadDir = public_path('uploads/education_pdfs');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            foreach ($request->file('pdfs') as $file) {
                $safeName   = Str::uuid() . '__' . $file->getClientOriginalName();
                $file->move($uploadDir, $safeName);
                $existing[] = 'uploads/education_pdfs/' . $safeName;
            }
        }
        $data['pdf_paths']  = count($existing) ? $existing : null;
        $data['sort_order'] = $data['sort_order'] ?? $education->sort_order;

        // 모델 fillable에 없는 키 제거 (pdfs, remove_pdfs, image 등)
        $allowed = ['title','category','description','title_en','description_en','title_jp','description_jp','image_path','pdf_paths','link','published_at','sort_order'];
        $data = array_intersect_key($data, array_flip($allowed));

        $education->update($data);

        return redirect()->route('education.index')->with('success', '교육 콘텐츠가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $education = Education::findOrFail($id);
        // 이미지 삭제
        if ($education->image_path) {
            $old = public_path($education->image_path);
            if (file_exists($old)) @unlink($old);
        }
        // PDF 파일 삭제
        foreach ($education->pdf_paths ?? [] as $path) {
            $full = public_path($path);
            if (file_exists($full)) @unlink($full);
        }
        $education->delete();
        return redirect()->route('education.index')->with('success', '교육 콘텐츠가 삭제되었습니다.');
    }

    // ── Public API  GET /admin/api/education ──
    public function apiIndex(Request $request)
    {
        $query = Education::orderBy('sort_order')->orderByDesc('published_at');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $items = $query->get()->map(function ($e) {
            return [
                'id'          => $e->id,
                'title'       => $e->title,
                'category'    => $e->category,
                'description' => $e->description,
                'image_url'   => $e->image_url,
                'link'        => $e->link,
                'date'        => $e->formatted_date,
                'pdf_count'   => count($e->pdf_files),
            ];
        });

        return response()->json($items)
            ->header('Access-Control-Allow-Origin', '*');
    }

    // ── Public API  GET /admin/api/education/{id} ──
    public function apiShow(string $id)
    {
        $e = Education::findOrFail($id);

        return response()->json([
            'id'          => $e->id,
            'title'       => $e->title,
            'category'    => $e->category,
            'description' => $e->description,
            'image_url'   => $e->image_url,
            'link'        => $e->link,
            'date'        => $e->formatted_date,
            'pdf_files'   => $e->pdf_files,   // [['name'=>'파일명.pdf','url'=>'/admin/...'], ...]
        ])->header('Access-Control-Allow-Origin', '*');
    }

    // ── Public API  POST /admin/api/education/{id}/apply ──
    public function apiApply(Request $request, string $id)
    {
        // CORS preflight
        if ($request->isMethod('OPTIONS')) {
            return response('', 204)
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, X-Requested-With');
        }

        $education = Education::findOrFail($id);

        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'email'      => 'required|email|max:200',
            'phone'      => 'nullable|string|max:30',
            'company'    => 'nullable|string|max:200',
            'jobtitle'   => 'nullable|string|max:100',
            'department' => 'nullable|string|max:100',
        ]);

        $data['education_id'] = $education->id;

        EducationApplicant::create($data);

        // 신청 완료 후 PDF 다운로드 정보 함께 반환
        return response()->json([
            'success'   => true,
            'message'   => '신청이 완료되었습니다.',
            'pdf_files' => $education->pdf_files,
        ])->header('Access-Control-Allow-Origin', '*');
    }
}
