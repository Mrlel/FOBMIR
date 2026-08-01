<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\Classeur;
use App\Models\Document;
use App\Models\Menage;
use App\Policies\ClasseurIndependantPolicy;
use App\Policies\DocumentIndependantPolicy;
use App\Policies\MenagePolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * Ces policies couvrent l'accès aux classeurs/documents du dossier personnel
     * des individus indépendants (guard "individu"). Les classeurs/documents de
     * ménage (guard "web") sont contrôlés séparément par MenagePolicy et ne
     * doivent pas être vérifiés via Gate::authorize sur Classeur/Document.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Classeur::class => ClasseurIndependantPolicy::class,
        Document::class => DocumentIndependantPolicy::class,
        Menage::class => MenagePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}
