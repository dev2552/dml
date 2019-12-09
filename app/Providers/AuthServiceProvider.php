<?php

namespace App\Providers;

use App\Models\DomainModel;
use App\Models\GroupModel;
use App\Models\IpModel;
use App\Models\PaymentModel;
use App\Models\ProviderModel;
use App\Models\RegistrarModel;
use App\Models\RequestModel;
use App\Models\RequestServerModel;
use App\Models\ServerModel;
use App\Models\StatusModel;
use App\Models\SubDomainModel;
use App\Policies\DomainPolicy;
use App\Policies\GroupPolicy;
use App\Policies\IpPolicy;
use App\Policies\PaymentPolicy;
use App\Policies\ProviderPolicy;
use App\Policies\RegistrarPolicy;
use App\Policies\RequestPolicy;
use App\Policies\RequestServerPolicy;
use App\Policies\ServerPolicy;
use App\Policies\StatusPolicy;
use App\Policies\SubDomainPolicy;
use App\Policies\UserPolicy;
use App\User;
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
        
         User::class => UserPolicy::class,
         DomainModel::class => DomainPolicy::class,
         RegistrarModel::class=>RegistrarPolicy::class,
         ServerModel::class=>ServerPolicy::class,
         RequestModel::class=>RequestPolicy::class,
         ProviderModel::class=>ProviderPolicy::class,
         IpModel::class=>IpPolicy::class,
         GroupModel::class=>GroupPolicy::class,
         PaymentModel::class=>PaymentPolicy::class,
         StatusModel::class=>StatusPolicy::class,
         RequestServerModel::class => RequestServerPolicy::class,
         SubDomainModel::class => SubDomainPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

    }
}
