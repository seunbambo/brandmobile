<?php

namespace App\Providers;

use App\Models\AnnualReturn;
use App\Models\Assessment;
use App\Models\Corporate;
use App\Models\Employee;
use App\Models\ReassessmentAppeal;
use App\Policies\AnnualReturnPolicy;
use App\Policies\AssessmentPolicy;
use App\Policies\CorporatePolicy;
use App\Policies\EmployeePolicy;
use App\Policies\ReassessmentAppealPolicy;
use App\Policies\UserPolicy;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Employee::class => EmployeePolicy::class,
        Corporate::class => CorporatePolicy::class,
        Assessment::class => AssessmentPolicy::class,
        AnnualReturn::class => AnnualReturnPolicy::class,
        ReassessmentAppeal::class => ReassessmentAppealPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // for API auth
        Passport::routes();
    }
}
