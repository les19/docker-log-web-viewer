<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AddUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:add-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to add users to the application';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userFileContent = Storage::get('users.json');

        if (is_null($userFileContent )) {
            $this->warn('users.json file not found in storage/app/private directory.');
            
            return Command::INVALID;
        }
        
        $userFileArray = json_decode($userFileContent, true);

        if (is_null($userFileArray)) {
            $this->error('Invalid JSON format in users.json file.');
            
            return Command::INVALID;
        }

        if ($message = Arr::get($userFileArray, 'message')) {
            $this->info($message);
        }

        if ($users = Arr::get($userFileArray, 'users')) {
            if (
                !is_array($users)
                || count($users) === 0
            ) {
                $this->warn('No users found in users.json file.');
                
                return Command::INVALID;
            }

            $table = [];

            foreach ($users as $user) {
                switch (gettype($user)) {
                    case 'string':
                        $password = $this->processStringUserKey($user);

                        $table[] = [$user, $password ?: 'Already exists'];

                        break;

                    default:
                        $this->error("Invalid user data format. Expected: string.");

                        break;
                }
            }

            $this->table(['User Email', 'Password'], $table);

            return Command::SUCCESS;
        }
        
        $this->warn('No "users" key found in users.json file or its empty.');
        
        return Command::INVALID;
    }

    public function processStringUserKey(string $userKey): string|false
    {
        // Assuming the user key is an email
        if (User::whereEmail($userKey)->exists()) {
            $this->info("User with email {$userKey} already exists.");

            return false;
        }

        $password = bin2hex(random_bytes(8));

        User::create([
            'email' => $userKey,
            'name' => $userKey, // Assuming the name is the same as the email
            
            'password' => Hash::make($password),
        ]);

        return $password;
    }

    public function validateEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
}
