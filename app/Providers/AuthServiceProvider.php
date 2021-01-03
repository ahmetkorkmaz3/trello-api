<?php

namespace App\Providers;

use App\Models\Board;
use App\Models\BoardUserInvite;
use App\Models\Card;
use App\Models\Column;
use App\Models\Team;
use App\Models\TeamUserInvite;
use App\Policies\BoardPolicy;
use App\Policies\BoardUserInvitePolicy;
use App\Policies\TeamPolicy;
use App\Policies\TeamUserInvitePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Board::class => BoardPolicy::class,
        Column::class => BoardPolicy::class,
        Card::class => BoardPolicy::class,
        Team::class => TeamPolicy::class,
        TeamUserInvite::class => TeamUserInvitePolicy::class,
        BoardUserInvite::class => BoardUserInvitePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
