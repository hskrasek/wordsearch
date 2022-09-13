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

        $user = $this->anticipate('Which user would you like to assign the role to?', function (mixed $input) {
            if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
                return User::pluck('email')->all();
            }

            return User::pluck('id')->all();
        });

        User::when(is_numeric($user), fn ($query) => $query->where('id', $user))->when(filter_var($user, FILTER_VALIDATE_EMAIL), fn ($query) => $query->where('email', $user))->first()->assignRole($role);

        return 0;
    }
}
