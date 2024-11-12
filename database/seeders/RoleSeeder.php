<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requiredRoles = ['admin', 'customer'];

        foreach ($requiredRoles as $key => $role) {
            // Verify 
            $inDatabase = Role::where('name',$role)->first();
            if(!$inDatabase){
                Role::create(['name'=>$role,'guard_name'=>'web']);
            }
        }
    }
}
