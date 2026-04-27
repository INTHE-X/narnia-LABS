<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index()
    {
        $members = User::orderBy('created_at', 'desc')->get();
        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'name'     => 'nullable|string|max:100',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|unique:users,email',
            'role'     => 'required|in:master,admin',
        ], [
            'username.required' => '아이디는 필수입니다.',
            'username.unique'   => '이미 사용 중인 아이디입니다.',
            'password.required' => '비밀번호는 필수입니다.',
            'password.min'      => '비밀번호는 8자 이상이어야 합니다.',
            'password.confirmed'=> '비밀번호 확인이 일치하지 않습니다.',
            'email.unique'      => '이미 사용 중인 이메일입니다.',
        ]);

        User::create([
            'username' => $request->username,
            'name'     => $request->name,
            'email'    => $request->email ?: null,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'role'     => $request->role,
        ]);

        return redirect()->route('members.index')->with('success', '관리자가 등록되었습니다.');
    }

    public function edit(string $id)
    {
        $member = User::findOrFail($id);
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, string $id)
    {
        $member = User::findOrFail($id);

        $rules = [
            'username' => 'required|string|max:100|unique:users,username,' . $id,
            'name'     => 'nullable|string|max:100',
            'phone'    => 'nullable|string|max:20',
            'email'    => 'nullable|email|unique:users,email,' . $id,
            'role'     => 'required|in:master,admin',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'string|min:8|confirmed';
        }

        $request->validate($rules, [
            'username.required' => '아이디는 필수입니다.',
            'username.unique'   => '이미 사용 중인 아이디입니다.',
            'password.min'      => '비밀번호는 8자 이상이어야 합니다.',
            'password.confirmed'=> '비밀번호 확인이 일치하지 않습니다.',
            'email.unique'      => '이미 사용 중인 이메일입니다.',
        ]);

        $data = [
            'username' => $request->username,
            'name'     => $request->name,
            'email'    => $request->email ?: null,
            'phone'    => $request->phone,
            'role'     => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $member->update($data);

        return redirect()->route('members.index')->with('success', '관리자 정보가 수정되었습니다.');
    }

    public function destroy(string $id)
    {
        $member = User::findOrFail($id);

        if ($member->id === auth()->id()) {
            return redirect()->route('members.index')->with('error', '현재 로그인된 계정은 삭제할 수 없습니다.');
        }

        $member->delete();

        return redirect()->route('members.index')->with('success', '관리자가 삭제되었습니다.');
    }
}
