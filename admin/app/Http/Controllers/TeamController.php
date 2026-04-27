<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::orderBy('sort_order')->orderBy('created_at')->get();
        return view('admin.team.index', compact('teams'));
    }

    public function create()
    {
        return view('admin.team.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'category'       => 'nullable|string|max:100',
            'description'    => 'required|string',
            'description_en' => 'nullable|string',
            'description_jp' => 'nullable|string',
            'image'          => 'nullable|image|max:5120',
            'sort_order'     => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/teams');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/teams/' . $filename;
        }

        $data['sort_order'] = $data['sort_order'] ?? 0;

        Team::create($data);

        return redirect()->route('team.index')->with('success', '팀 정보가 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $team = Team::findOrFail($id);
        return view('admin.team.edit', compact('team'));
    }

    public function update(Request $request, string $id)
    {
        $team = Team::findOrFail($id);

        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'category'       => 'nullable|string|max:100',
            'description'    => 'required|string',
            'description_en' => 'nullable|string',
            'description_jp' => 'nullable|string',
            'image'          => 'nullable|image|max:5120',
            'sort_order'     => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // 기존 이미지 삭제
            if ($team->image_path) {
                $oldPath = public_path($team->image_path);
                if (file_exists($oldPath)) @unlink($oldPath);
            }
            $uploadDir = public_path('uploads/teams');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/teams/' . $filename;
        }

        $data['sort_order'] = $data['sort_order'] ?? $team->sort_order;

        $team->update($data);

        return redirect()->route('team.index')->with('success', '팀 정보가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $team = Team::findOrFail($id);

        if ($team->image_path) {
            $oldPath = public_path($team->image_path);
            if (file_exists($oldPath)) @unlink($oldPath);
        }

        $team->delete();

        return redirect()->route('team.index')->with('success', '팀 정보가 삭제되었습니다.');
    }

    // ──────────────────────────────────────────────
    // Public API  GET /admin/api/teams
    // ──────────────────────────────────────────────
    public function apiIndex()
    {
        $teams = Team::orderBy('sort_order')->orderBy('created_at')->get()->map(function ($t) {
            return [
                'id'             => $t->id,
                'title'          => $t->title,
                'category'       => $t->category,
                'description'    => $t->description,
                'description_en' => $t->description_en,
                'description_jp' => $t->description_jp,
                'image_url'      => $t->image_path ? '/admin/' . $t->image_path : null,
                'sort_order'     => $t->sort_order,
            ];
        });

        return response()->json($teams)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate')
            ->header('Pragma', 'no-cache');
    }
}
