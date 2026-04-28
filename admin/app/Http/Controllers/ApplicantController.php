<?php

namespace App\Http\Controllers;

use App\Models\Education;
use App\Models\EducationApplicant;
use Illuminate\Http\Request;

class ApplicantController extends Controller
{
    /**
     * 신청자 목록
     * URL: /admin/applicants?education_id=1
     */
    public function index(Request $request)
    {
        $educationId = $request->query('education_id');

        $query = EducationApplicant::with('education')
            ->orderByDesc('created_at');

        if ($educationId) {
            $query->where('education_id', $educationId);
        }

        $applicants    = $query->paginate(20)->withQueryString();
        $educations    = Education::orderBy('sort_order')->orderByDesc('published_at')->get();
        $educationTitle = $educationId
            ? optional(Education::find($educationId))->title ?? '전체'
            : '전체';

        return view('admin.applicants.index', compact('applicants', 'educations', 'educationTitle', 'educationId'));
    }

    /**
     * 신청자 상세
     */
    public function show(string $id)
    {
        $applicant = EducationApplicant::with('education')->findOrFail($id);
        return view('admin.applicants.show', compact('applicant'));
    }

    /**
     * 신청자 삭제
     */
    public function destroy(string $id)
    {
        EducationApplicant::findOrFail($id)->delete();

        return redirect()->back()
            ->with('success', '신청자가 삭제되었습니다.');
    }

    /**
     * 선택 삭제 (bulk delete)
     */
    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids', []);
        if (!empty($ids)) {
            EducationApplicant::whereIn('id', $ids)->delete();
        }
        return redirect()->back()->with('success', count($ids) . '명의 신청자가 삭제되었습니다.');
    }
}
