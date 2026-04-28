@extends('layouts.admin')
@section('title', 'Resource 메뉴 관리')
@section('content')
<div class="content-header">
    <div class="content-header-inner">
        <h1 class="page-title">Resource 메뉴 관리</h1>
        <div class="header-actions">
            <a href="{{ route('resource-menus.create') }}" class="btn btn-primary">메뉴 추가</a>
        </div>
    </div>
</div>
<div class="content-body">
    @if(session('success'))<div data-flash-success="{{ session('success') }}"></div>@endif

    <div class="card">
        <div class="card-header">
            <div class="card-title">헤더 Resource 드롭다운 메뉴 목록</div>
        </div>
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="text-align:center;width:60px;">순서</th>
                        <th>메뉴명</th>
                        <th>링크 URL</th>
                        <th>새 탭</th>
                        <th>노출</th>
                        <th>관리</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $menu)
                    <tr data-href="{{ route('resource-menus.edit', $menu->id) }}" style="cursor:pointer;">
                        <td style="text-align:center;color:#999;">{{ $menu->sort_order }}</td>
                        <td style="font-weight:600;">{{ $menu->title }}</td>
                        <td style="color:#0066cc;font-size:12px;max-width:300px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            <a href="{{ $menu->url }}" target="_blank" onclick="event.stopPropagation();" style="color:#0066cc;">{{ $menu->url }}</a>
                        </td>
                        <td>
                            @if($menu->is_external)
                                <span style="font-size:11px;background:#e8f4fd;color:#0066cc;padding:2px 8px;border-radius:10px;">새 탭</span>
                            @else
                                <span style="font-size:11px;background:#f5f5f5;color:#888;padding:2px 8px;border-radius:10px;">현재 탭</span>
                            @endif
                        </td>
                        <td>
                            @if($menu->is_active)
                                <span style="font-size:11px;background:#e8f8e8;color:#2e7d2e;padding:2px 8px;border-radius:10px;">노출</span>
                            @else
                                <span style="font-size:11px;background:#fee;color:#c00;padding:2px 8px;border-radius:10px;">숨김</span>
                            @endif
                        </td>
                        <td onclick="event.stopPropagation();">
                            <a href="{{ route('resource-menus.edit', $menu->id) }}" class="btn btn-secondary btn-sm" style="font-size:11px;padding:3px 10px;">수정</a>
                            <form method="POST" action="{{ route('resource-menus.destroy', $menu->id) }}"
                                  onsubmit="return confirm('삭제하시겠습니까?');" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm" style="font-size:11px;padding:3px 10px;">삭제</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <p>등록된 메뉴가 없습니다. 메뉴 추가 버튼을 눌러 추가하세요.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card" style="margin-top:16px;">
        <div class="card-header"><div class="card-title" style="font-size:13px;">💡 안내</div></div>
        <div class="card-body" style="padding:16px 24px;font-size:13px;color:#666;line-height:1.8;">
            여기서 추가한 메뉴는 사이트 헤더의 <strong>Resource</strong> 드롭다운 하단에 자동으로 표시됩니다.<br>
            기존 고정 메뉴 (Case studies, Education, Events, Publications) 아래에 순서대로 나타납니다.
        </div>
    </div>
</div>
@endsection
