<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Research;
use App\Models\ResearchComplete;
use App\Models\ResearchPresentation;
use App\Models\ResearchPublication;
use App\Models\ResearchCopyright;
use App\Models\ResearchCitation;
use App\Models\ResearchUtilization;
use App\Models\Announcement;
use App\Models\Currency;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\ResearchForm;
use App\Models\FormBuilder\InventionForm;

use App\Models\Authentication\Permission;
use App\Policies\Authentication\RolePolicy;
use App\Policies\Authentication\PermissionPolicy;
use App\Policies\Authentication\UserPolicy;
use App\Policies\Research\ResearchPolicy;
use App\Policies\Research\ResearchCompletionPolicy;
use App\Policies\Research\ResearchPresentationPolicy;
use App\Policies\Research\ResearchPublicationPolicy;
use App\Policies\Research\ResearchCopyrightPolicy;
use App\Policies\Research\ResearchUtilizationPolicy;
use App\Policies\Research\ResearchCitationPolicy;
use App\Policies\Content\AnnouncementPolicy;
use App\Policies\Maintenance\CurrencyPolicy;
use App\Policies\Maintenance\DropdownPolicy;
use App\Policies\Maintenance\Research\ResearchFormPolicy;
use App\Policies\Maintenance\Invention\InventionFormPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        User::class => UserPolicy::class,

        //Faculty Research
        Research::class => ResearchPolicy::class,
        ResearchComplete::class => ResearchCompletionPolicy::class,
        ResearchPresentation::class => ResearchPresentationPolicy::class,
        ResearchPublication::class => ResearchPublicationPolicy::class,
        ResearchCitation::class => ResearchCitationPolicy::class,
        ResearchCopyright::class => ResearchCopyrightPolicy::class,
        ResearchUtilization::class => ResearchUtilizationPolicy::class,

        //Content Management
        Announcement::class => AnnouncementPolicy::class,

        //Maintenances
        Currency::class => CurrencyPolicy::class,
        Dropdown::class => DropdownPolicy::class,
        ResearchForm::class => ResearchFormPolicy::class,
        InventionForm::class => InventionFormPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // $this->generalPolicies();
        //
    }

}
