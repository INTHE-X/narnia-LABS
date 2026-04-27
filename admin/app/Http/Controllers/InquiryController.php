<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::orderByDesc('created_at')->get();
        return view('admin.inquiry.index', compact('inquiries'));
    }

    public function show(string $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('admin.inquiry.show', compact('inquiry'));
    }

    public function destroy(string $id)
    {
        Inquiry::findOrFail($id)->delete();
        return redirect()->route('inquiry.index')->with('success', '문의가 삭제되었습니다.');
    }

    // ── Public API  POST /admin/api/inquiries ──
    public function apiStore(Request $request)
    {
        $data = $request->validate([
            'company'  => 'required|string|max:200',
            'name'     => 'required|string|max:100',
            'position' => 'nullable|string|max:100',
            'email'    => 'required|email|max:200',
            'phone'    => 'nullable|string|max:50',
            'message'  => 'required|string',
        ]);

        $inquiry = Inquiry::create($data);

        return response()->json(['success' => true, 'id' => $inquiry->id], 201)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type');
    }

    // OPTIONS preflight 대응
    public function apiOptions()
    {
        return response('', 204)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'POST, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type');
    }
}
