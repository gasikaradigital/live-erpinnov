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
    public $entreprises = [];
    public $showTerminerButton = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'nif' => 'nullable|string|unique:entreprises',
        'ville' => 'required|string|max:255',
        'pays' => 'nullable|string|max:255',
        'phone' => 'required|string|max:255',
        'adresse' => 'required|string',
    ];

    public function mount()
    {
        $this->entreprises = auth()->user()->entreprises()->get();
        $this->showTerminerButton = $this->entreprises->isNotEmpty();
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
            ]);

            $this->entreprises->push($entreprise);
            $this->showTerminerButton = true;

            $this->reset(['name', 'nif', 'ville', 'pays', 'phone', 'adresse']);
            $this->alert('success', 'Entreprise ajoutée avec succès. Vous pouvez en ajouter une autre ou terminer.');
            return redirect()->route('entreprise.create');
        } catch (\Exception $e) {
            $this->alert('error', 'Une erreur est survenue lors de l\'ajout de l\'entreprise: ' . $e->getMessage());
        }
    }

    public function terminer()
    {
        return redirect()->route('espaceClient');
    }

    public function render()
    {
        return view('livewire.create-entreprise')->layout('layouts.main');
    }
}
