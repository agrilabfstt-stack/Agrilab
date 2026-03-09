<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin account
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@agrilab.dz',
            'password' => Hash::make('Admin@1234'),
            'role'     => 'admin',
        ]);

        // Sample professors
        $prof1 = User::create([
            'name'     => 'Prof. Ahmed Benali',
            'email'    => 'ahmed.benali@agrilab.dz',
            'password' => Hash::make('Prof@1234'),
            'role'     => 'professor',
        ]);

        $prof2 = User::create([
            'name'     => 'Prof. Fatima Zohra',
            'email'    => 'fatima.zohra@agrilab.dz',
            'password' => Hash::make('Prof@1234'),
            'role'     => 'professor',
        ]);

        // Sample students
        User::create([
            'name'         => 'Karim Hadj',
            'email'        => 'karim.hadj@agrilab.dz',
            'password'     => Hash::make('Student@1234'),
            'role'         => 'student',
            'professor_id' => $prof1->id,
        ]);

        User::create([
            'name'         => 'Sarah Amrani',
            'email'        => 'sarah.amrani@agrilab.dz',
            'password'     => Hash::make('Student@1234'),
            'role'         => 'student',
            'professor_id' => $prof1->id,
        ]);

        User::create([
            'name'         => 'Youcef Brahim',
            'email'        => 'youcef.brahim@agrilab.dz',
            'password'     => Hash::make('Student@1234'),
            'role'         => 'student',
            'professor_id' => $prof2->id,
        ]);

        // Sample categories
        $categories = [
            ['name' => 'Agronomie',        'color' => '#10b981', 'description' => 'Sciences des cultures et de la production végétale'],
            ['name' => 'Zootechnie',        'color' => '#3b82f6', 'description' => 'Élevage et productions animales'],
            ['name' => 'Agroécologie',      'color' => '#84cc16', 'description' => 'Agriculture durable et respect de l\'environnement'],
            ['name' => 'Hydraulique',       'color' => '#06b6d4', 'description' => 'Irrigation et gestion de l\'eau'],
            ['name' => 'Phytopathologie',   'color' => '#f59e0b', 'description' => 'Maladies des plantes et protection des cultures'],
        ];

        foreach ($categories as $cat) {
            Category::create($cat);
        }
    }
}
