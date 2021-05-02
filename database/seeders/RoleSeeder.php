<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private array $roles = [
        'owner' => 'Owner',
        'admin' => 'Admin',
        'moderator' => 'Moderator'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->roles as $type => $title) {
            Role::create([
                'type' => $type,
                'title' => $title
            ]);
        }
    }
}
