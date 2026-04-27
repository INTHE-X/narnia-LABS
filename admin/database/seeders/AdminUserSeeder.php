<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * 기본 관리자 계정 생성
     * 아이디: admin
     * 비밀번호: admin1234
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@narnialabs.com'],
            [
                'name'     => 'admin',
                'email'    => 'admin@narnialabs.com',
                'password' => Hash::make('admin1234'),
            ]
        );
    }
}
