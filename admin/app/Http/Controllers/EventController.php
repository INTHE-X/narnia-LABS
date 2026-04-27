<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('sort_order')->orderByDesc('start_date')->get();
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'title_en'       => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:500',
            'description_jp' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'image'       => 'nullable|image|max:5120',
            'link'        => 'nullable|url|max:500',
            'is_featured' => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            $uploadDir = public_path('uploads/events');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/events/' . $filename;
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order']  = $data['sort_order'] ?? 0;

        Event::create($data);

        return redirect()->route('events.index')->with('success', '이벤트가 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $event = Event::findOrFail($id);
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, string $id)
    {
        $event = Event::findOrFail($id);

        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string',
            'title_en'       => 'nullable|string|max:500',
            'description_en' => 'nullable|string',
            'title_jp'       => 'nullable|string|max:500',
            'description_jp' => 'nullable|string',
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'image'       => 'nullable|image|max:5120',
            'link'        => 'nullable|url|max:500',
            'is_featured' => 'nullable|boolean',
            'sort_order'  => 'nullable|integer',
        ]);

        if ($request->hasFile('image')) {
            // 기존 이미지 삭제
            if ($event->image_path) {
                $oldPath = public_path($event->image_path);
                if (file_exists($oldPath)) @unlink($oldPath);
            }
            $uploadDir = public_path('uploads/events');
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $filename = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move($uploadDir, $filename);
            $data['image_path'] = 'uploads/events/' . $filename;
        }

        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order']  = $data['sort_order'] ?? $event->sort_order;

        $event->update($data);

        return redirect()->route('events.index')->with('success', '이벤트가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $event = Event::findOrFail($id);

        if ($event->image_path) {
            $oldPath = public_path($event->image_path);
            if (file_exists($oldPath)) @unlink($oldPath);
        }

        $event->delete();

        return redirect()->route('events.index')->with('success', '이벤트가 삭제되었습니다.');
    }

    // ──────────────────────────────────────────────
    // Public API  GET /admin/api/events
    // ──────────────────────────────────────────────
    public function apiIndex(Request $request)
    {
        $query = Event::orderBy('sort_order')->orderByDesc('start_date');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $events = $query->get()->map(function ($e) {
            return [
                'id'          => $e->id,
                'title'       => $e->title,
                'title_en'    => $e->title_en,
                'title_jp'    => $e->title_jp,
                'category'    => $e->category,
                'description' => $e->description,
                'description_en' => $e->description_en,
                'description_jp' => $e->description_jp,
                'start_date'  => $e->start_date?->format('F j, Y'),
                'end_date'    => $e->end_date?->format('F j, Y'),
                'image_url'   => $e->image_url,
                'link'        => $e->link,
                'is_featured' => $e->is_featured,
            ];
        });

        return response()->json($events)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
