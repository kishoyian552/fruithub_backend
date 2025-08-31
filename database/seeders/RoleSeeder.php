<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'admin', 'description' => 'This is a role for an administrator.'],
            ['name' => 'user', 'description' => 'This is a role for a normal user.'],
            ['name' => 'teacher', 'description' => 'This is a role for a teacher.'],
            ['name' => 'student', 'description' => 'This is a role for a student.'],
        ];// predefined roles

        foreach ($roles as $role) {
            Role::create($role);
        }// seed roles
    }
}
