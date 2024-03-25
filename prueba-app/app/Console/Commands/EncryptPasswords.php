<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class EncryptPasswords extends Command
{
    protected $signature = 'encrypt:passwords';
    protected $description = 'Encrypt all passwords in the database with bcrypt';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Obtener todos los usuarios de la base de datos
        $users = User::all();

        foreach ($users as $user) {
            // Encriptar la contraseña si no está encriptada
            if (!Hash::needsRehash($user->password)) {
                $user->password = Hash::make($user->password);
                $user->save();
                $this->info('Password for user ' . $user->email . ' encrypted successfully.');
            } else {
                $this->info('Password for user ' . $user->email . ' is already hashed.');
            }
        }

        $this->info('All passwords encrypted successfully.');
    }
}
