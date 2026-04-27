<?php

namespace App\Http\Controllers;

use App\Models\ResourceMenu;
use Illuminate\Http\Request;

class ResourceMenuController extends Controller
{
    public function index()
    {
        $menus = ResourceMenu::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.resource_menus.index', compact('menus'));
    }

    public function create()
    {
        return view('admin.resource_menus.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:100',
            'url'        => 'required|string|max:500',
            'is_external'=> 'boolean',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        ResourceMenu::create([
            'title'       => $request->title,
            'url'         => $request->url,
            'is_external' => $request->boolean('is_external', true),
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $request->input('sort_order', 0),
        ]);

        return redirect()->route('resource-menus.index')
            ->with('success', '메뉴가 추가되었습니다.');
    }

    public function edit(string $id)
    {
        $menu = ResourceMenu::findOrFail($id);
        return view('admin.resource_menus.edit', compact('menu'));
    }

    public function update(Request $request, string $id)
    {
        $menu = ResourceMenu::findOrFail($id);

        $request->validate([
            'title'      => 'required|string|max:100',
            'url'        => 'required|string|max:500',
            'is_external'=> 'boolean',
            'is_active'  => 'boolean',
            'sort_order' => 'nullable|integer',
        ]);

        $menu->update([
            'title'       => $request->title,
            'url'         => $request->url,
            'is_external' => $request->boolean('is_external', true),
            'is_active'   => $request->boolean('is_active', true),
            'sort_order'  => $request->input('sort_order', 0),
        ]);

        return redirect()->route('resource-menus.index')
            ->with('success', '메뉴가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        ResourceMenu::findOrFail($id)->delete();
        return redirect()->route('resource-menus.index')
            ->with('success', '메뉴가 삭제되었습니다.');
    }

    // ── Public API  GET /admin/api/resource-menus ──
    public function apiIndex()
    {
        $menus = ResourceMenu::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->map(fn($m) => [
                'title'       => $m->title,
                'url'         => $m->url,
                'is_external' => $m->is_external,
            ]);

        return response()->json($menus)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
