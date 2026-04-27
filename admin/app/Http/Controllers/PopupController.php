<?php

namespace App\Http\Controllers;

use App\Models\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PopupController extends Controller
{
    public function apiIndex()
    {
        $today = now()->startOfDay();

        $popups = Popup::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function ($popup) use ($today) {
                // start_date 체크
                if ($popup->start_date && $popup->start_date->gt($today)) return false;
                // end_date 체크
                if ($popup->end_date && $popup->end_date->lt($today)) return false;
                return true;
            })
            ->map(function ($popup) {
                return [
                    'id'       => $popup->id,
                    'title'    => $popup->title,
                    'image'    => $popup->image_path ? '/admin/' . $popup->image_path : null,
                    'link_url' => $popup->link_url,
                    'position' => $popup->position ?? 'center',
                    'show_dim' => $popup->show_dim ?? true,
                ];
            })
            ->values();

        return response()->json($popups)
            ->header('Access-Control-Allow-Origin', '*');
    }

    public function index()
    {
        $popups = Popup::orderBy('created_at', 'desc')->get();
        return view('admin.popup.index', compact('popups'));
    }

    public function create()
    {
        return view('admin.popup.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'status'     => 'required|in:active,inactive',
            'position'   => 'nullable|in:center,bottom-right',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'image'      => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'link_url'   => 'nullable|url',
        ], [
            'title.required'            => '팝업 제목은 필수입니다.',
            'end_date.after_or_equal'   => '종료일은 시작일 이후여야 합니다.',
            'image.image'               => '이미지 파일만 업로드 가능합니다.',
            'image.max'                 => '이미지는 5MB 이하여야 합니다.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file     = $request->file('image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/popups');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $file->move($uploadDir, $filename);
            $imagePath = 'uploads/popups/' . $filename;
        }

        Popup::create([
            'title'      => $request->title,
            'image_path' => $imagePath,
            'link_url'   => $request->link_url,
            'start_date' => $request->start_date ?: null,
            'end_date'   => $request->end_date ?: null,
            'status'     => $request->status,
            'position'   => $request->position ?? 'center',
            'show_dim'   => $request->boolean('show_dim'),
        ]);

        return redirect()->route('popup.index')->with('success', '팝업이 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $popup = Popup::findOrFail($id);
        return view('admin.popup.edit', compact('popup'));
    }

    public function update(Request $request, string $id)
    {
        $popup = Popup::findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:255',
            'status'     => 'required|in:active,inactive',
            'position'   => 'nullable|in:center,bottom-right',
            'show_dim'   => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'image'      => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:10240',
            'link_url'   => 'nullable|url',
        ], [
            'title.required'            => '팝업 제목은 필수입니다.',
            'end_date.after_or_equal'   => '종료일은 시작일 이후여야 합니다.',
        ]);

        $imagePath = $popup->image_path;
        if ($request->hasFile('image')) {
            // 기존 이미지 삭제
            if ($imagePath && file_exists(public_path($imagePath))) {
                @unlink(public_path($imagePath));
            }
            $file     = $request->file('image');
            $filename = time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $uploadDir = public_path('uploads/popups');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $file->move($uploadDir, $filename);
            $imagePath = 'uploads/popups/' . $filename;
        }

        $popup->update([
            'title'      => $request->title,
            'image_path' => $imagePath,
            'link_url'   => $request->link_url,
            'start_date' => $request->start_date ?: null,
            'end_date'   => $request->end_date ?: null,
            'status'     => $request->status,
            'position'   => $request->position ?? 'center',
            'show_dim'   => $request->boolean('show_dim'),
        ]);

        return redirect()->route('popup.index')->with('success', '팝업이 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $popup = Popup::findOrFail($id);

        if ($popup->image_path && file_exists(public_path($popup->image_path))) {
            @unlink(public_path($popup->image_path));
        }

        $popup->delete();

        return redirect()->route('popup.index')->with('success', '팝업이 삭제되었습니다.');
    }
}
