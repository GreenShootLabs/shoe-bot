<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Create an admin user in the system';

    public function handle()
    {
        $user = new User();

        $first = $this->ask('First Name');
        $last = $this->ask('Last Name');

        $user->name = "$first $last";

        $user->email = $this->ask('Email');
        $user->phone_number = $this->ask('Phone number (with country code)');

        $password = $this->createPassword();

        $user->password = Hash::make($password);

        $user->save();

        $this->info("User created with id {$user->id}");
    }

    private function createPassword(): string
    {
        $password1 = $this->secret('Password');
        $password2 = $this->secret('Repeat password');

        if ($password1 != $password2) {
            $this->warn("Passwords do not match!");
            return $this->createPassword();
        }

        return $password1;
    }
}
