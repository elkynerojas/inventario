<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListUsers extends Command
{
    protected $signature = 'users:list';
    protected $description = 'List all users in the database';

    public function handle()
    {
        $users = User::with('rol')->get();
        
        $this->info('Usuarios en la base de datos:');
        $this->newLine();
        
        foreach ($users as $user) {
            $rol = $user->rol ? $user->rol->nombre : 'Sin rol';
            $this->line("• {$user->name} ({$user->email}) - Rol: {$rol}");
        }
        
        $this->newLine();
        $this->info("Total: {$users->count()} usuarios");
    }
}