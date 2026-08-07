<?php

namespace App\Providers;

// Pastikan import semua kelas yang diperlukan
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate; 

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Daftarkan Policy Anda di sini:
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Panggil parent::boot() jika ada logika tambahan yang diperlukan
        // $this->registerPolicies();
    }
}