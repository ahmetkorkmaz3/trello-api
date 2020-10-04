<?php

namespace App\Providers;

use App\Models\Board;
use App\Models\Card;
use App\Models\Column;
use App\Models\Team;
use App\Models\TeamBoard;
use App\Policies\BoardPolicy;
use App\Policies\TeamPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
        Board::class => BoardPolicy::class,
        Column::class => BoardPolicy::class,
        Card::class => BoardPolicy::class,
        Team::class => TeamPolicy::class,
        TeamBoard::class => BoardPolicy::class,
//        'App\Model\Board' => 'App\Policies\BoardPolicy',
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
