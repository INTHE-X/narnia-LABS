<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function index()
    {
        try {
            $clients = Client::orderBy('sort_order')->orderBy('id')->get();
        } catch (\Exception $e) {
            $clients = collect([]);
        }
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:200',
            'image'      => 'required|mimes:jpg,jpeg,png,gif,svg,webp|max:5120',
            'link'       => 'nullable|url|max:500',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/clients');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/clients/' . $filename;
        }

        $data['is_active']  = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? 0;

        Client::create($data);

        return redirect()->route('clients.index')->with('success', '클라이언트 로고가 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, string $id)
    {
        $client = Client::findOrFail($id);

        $data = $request->validate([
            'name'       => 'required|string|max:200',
            'image'      => 'nullable|mimes:jpg,jpeg,png,gif,svg,webp|max:5120',
            'link'       => 'nullable|url|max:500',
            'is_active'  => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            if ($client->image_path) {
                $old = public_path($client->image_path);
                if (file_exists($old)) @unlink($old);
            }
            $uploadDir = public_path('uploads/clients');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/clients/' . $filename;
        }

        $data['is_active']  = $request->boolean('is_active');
        $data['sort_order'] = $data['sort_order'] ?? $client->sort_order;

        $client->update($data);

        return redirect()->route('clients.index')->with('success', '클라이언트 로고가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $client = Client::findOrFail($id);
        if ($client->image_path) {
            $old = public_path($client->image_path);
            if (file_exists($old)) @unlink($old);
        }
        $client->delete();
        return redirect()->route('clients.index')->with('success', '클라이언트 로고가 삭제되었습니다.');
    }

    // ── Public API ──
    public function apiIndex()
    {
        try {
            $clients = Client::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get()
                ->map(fn($c) => [
                    'id'        => $c->id,
                    'name'      => $c->name,
                    'image_url' => $c->image_url,
                    'link'      => $c->link,
                ]);
        } catch (\Exception $e) {
            $clients = collect([]);
        }

        return response()->json($clients)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
