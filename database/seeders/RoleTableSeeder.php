<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\User;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Role();
        $role->name = 'admin';
        $role->description = 'Administrador';
        $role->save();
        $role = new Role();
        $role->name = 'user';
        $role->description = 'Usuario';
        $role->save();
        $user = User::create(['name'       => 'Federico Nuño',
                              'email'      => 'federico.nuno@lob.com.mx',
                              'password'   => Hash::make('Jn_2020!')]);
        $user->roles()->attach(Role::where('name', 'admin')->first());
    }
}
