<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Entreprise;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CreateEntreprise extends Component
{
    use WithPagination, LivewireAlert, AuthorizesRequests;

    public $name;
    public $nif;
    public $ville;
    public $pays;
    public $phone;
    public $adresse;
    public $employees_count;
    public $entreprises = [];
    public $showTerminerButton = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'nif' => 'nullable|string|unique:entreprises',
        'ville' => 'required|string|max:255',
        'pays' => 'nullable|string|max:255',
        'phone' => 'required|string|max:255',
        'adresse' => 'required|string',
        'employees_count' => 'required|string'
    ];

    public function mount()
    {
        // Vérifier si l'utilisateur a déjà des entreprises
        $this->entreprises = auth()->user()->entreprises()->get();
        $this->showTerminerButton = $this->entreprises->isNotEmpty();

        // Si l'utilisateur a déjà une entreprise et un plan sélectionné
        if ($this->showTerminerButton && session()->has('selected_plan')) {
            return redirect()->route('instance.create');
        }

        // Si l'utilisateur a déjà une entreprise mais pas de plan
        if ($this->showTerminerButton && !session()->has('selected_plan')) {
            return redirect()->route('plans.selection');
        }
    }

    public function store()
    {
        $this->validate();

        try {
            $entreprise = auth()->user()->entreprises()->create([
                'name' => $this->name,
                'nif' => $this->nif,
                'ville' => $this->ville,
                'pays' => $this->pays ?? '',
                'phone' => $this->phone,
                'adresse' => $this->adresse,
                'employees_count' => $this->employees_count
            ]);

            $this->entreprises->push($entreprise);
            $this->showTerminerButton = true;

            $this->reset(['name', 'nif', 'ville', 'pays', 'phone', 'adresse', 'employees_count']);
            // Message de succès avec instruction pour la prochaine étape
            $this->alert('success', 'Entreprise ajoutée avec succès. Vous pouvez en ajouter une autre ou continuer vers la sélection des plans.');
            // Toujours rediriger vers la sélection des plans après création
            // Redirection vers la sélection des plans si c'est la première entreprise
            if ($this->entreprises->count() === 1) {
                return redirect()->route('plans.selection');
            }

            return redirect()->route('entreprise.create');
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue lors de l\'ajout de l\'entreprise: ' . $e->getMessage());
        }
    }

    public function terminer()
    {
        // Rediriger vers la sélection des plans si aucun plan n'est sélectionné
        if (!session()->has('selected_plan')) {
            return redirect()->route('plans.selection');
        }

        // Si un plan est sélectionné mais pas d'instance
        if (!auth()->user()->instances()->exists()) {
            return redirect()->route('instance.create');
        }

        // Si tout est configuré, rediriger vers l'espace client
        return redirect()->route('espaceClient');
    }

    public function render()
    {
        return view('livewire.create-entreprise', [
            'nextStep' => $this->showTerminerButton ? 'plans' : 'entreprise'
        ])->layout('layouts.main');
    }
}
