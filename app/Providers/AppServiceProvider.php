<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \App\Models\SuratKeluar::observe(\App\Observers\SuratKeluarObserver::class);
        \App\Models\Disposisi::observe(\App\Observers\DisposisiObserver::class);

        // Register Policies Manually
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Tembusan::class, \App\Policies\TembusanPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\ReviewSurat::class, \App\Policies\ReviewSuratPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\ArsipDigital::class, \App\Policies\ArsipDigitalPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\App\Models\Splaner::class, \App\Policies\SplanerPolicy::class);
        \Illuminate\Support\Facades\Gate::policy(\Spatie\Activitylog\Models\Activity::class, \App\Policies\ActivityPolicy::class);

        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            return $user->hasRole('super_admin') ? true : null;
        });
    }
}
