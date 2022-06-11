<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class AssignRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permission:assign-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign a role to a user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $role = $this->anticipate('Which role would you like to assign to the user?', Role::all()->pluck('name')->toArray());

        $email = $this->anticipate('Which user would you like to assign the role to?', User::registered()->pluck('email')->toArray());

        User::whereEmail($email)->first()->assignRole($role);

        return 0;
    }
}
