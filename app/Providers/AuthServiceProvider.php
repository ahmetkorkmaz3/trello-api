<?php

namespace App\Providers;

use App\Http\Controllers\Api\TeamBoardController;
use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\Team;
use App\Policies\BoardPolicy;
use App\Policies\TeamBoardPolicy;
use App\Policies\TeamPolicy;
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
        TeamBoardController::class => TeamBoardPolicy::class,
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
