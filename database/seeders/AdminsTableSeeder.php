<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admins = [
            [
                'email' => '',
                'password' => '',
                'name' => '',
                'role' => 'Admin',
            ],
            [
                'email' => 'k',
                'password' => '',
                'name' => '',
                'role' => 'Admin',
            ],
            [
                'email' => '',
                'password' => '',
                'name' => '',
                'role' => 'Admin',
            ],
        ];

        foreach ($admins as $row) {
            /** @var Admin $admin */
            $admin = Admin::where('email', '=', $row['email'])->first();

            if (!$admin) {
                $admin = new Admin();
                $admin->email = $row['email'];
            }

            $admin->password = bcrypt($row['password']);
            $admin->name = $row['name'];
            $admin->save();
        }
    }
}
