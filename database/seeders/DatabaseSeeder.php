<?php

namespace Database\Seeders;

use App\Models\Folder;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──
        $admin = User::create([
            'name'     => 'Admin Système',
            'email'    => 'admin@ged.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'status'   => 'actif',
        ]);

        // ── Manager ──
        $manager = User::create([
            'name'     => 'Sarah Dupont',
            'email'    => 'sarah@ged.com',
            'password' => Hash::make('password'),
            'role'     => 'manager',
            'status'   => 'actif',
        ]);

        // ── Collaborateur ──
        User::create([
            'name'     => 'Mohamed Ali',
            'email'    => 'mohamed@ged.com',
            'password' => Hash::make('password'),
            'role'     => 'collaborateur',
            'status'   => 'actif',
        ]);

        // ── En attente ──
        User::create([
            'name'     => 'Jean Martin',
            'email'    => 'jean@ged.com',
            'password' => Hash::make('password'),
            'role'     => 'collaborateur',
            'status'   => 'en_attente',
        ]);

        // ── Bloqué ──
        User::create([
            'name'     => 'Invité Externe',
            'email'    => 'invite@ged.com',
            'password' => Hash::make('password'),
            'role'     => 'lecteur',
            'status'   => 'bloque',
        ]);

        // ── Dossiers pour l'admin ──
        Folder::create(['name' => 'Projets Marketing', 'user_id' => $admin->id]);
        Folder::create(['name' => 'Documents RH',      'user_id' => $admin->id]);
        Folder::create(['name' => 'Factures 2024',     'user_id' => $admin->id]);
        Folder::create(['name' => 'Design Assets',     'user_id' => $admin->id]);

        // ── Dossiers pour le manager ──
        Folder::create(['name' => 'Mes Rapports',   'user_id' => $manager->id]);
        Folder::create(['name' => 'Présentations',  'user_id' => $manager->id]);
    }
}