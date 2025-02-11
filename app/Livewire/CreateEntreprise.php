<?php

namespace App\Livewire;

use App\Models\Subscription;
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
   public $showBackHeader = false;
   public $hasSubscription;

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
       $user = auth()->user();

       $this->hasSubscription = $user->subscriptions()
           ->whereIn('status', ['active', 'trial'])
           ->exists();

       $this->entreprises = $user->entreprises()->get();
       $this->showTerminerButton = $this->entreprises->isNotEmpty();
       $this->showBackHeader = $this->entreprises->isNotEmpty() || $this->hasSubscription;

       // Supprimons ces redirections qui empêchent l'ajout de nouvelles entreprises
       /*if ($this->showTerminerButton && session()->has('selected_plan')) {
           return redirect()->route('instance.create');
       }

       if ($this->showTerminerButton && !session()->has('selected_plan')) {
           return redirect()->route('plans.selection');
       }*/
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
           $this->showBackHeader = true;

           $this->reset(['name', 'nif', 'ville', 'pays', 'phone', 'adresse', 'employees_count']);

           $this->alert('success', 'Entreprise ajoutée avec succès. Vous pouvez en ajouter une autre ou continuer.');

           // Redirection selon nombre d'entreprises
           if ($this->entreprises->count() === 1) {
               return redirect()->route('plans.selection');
           }

           return redirect()->route('entreprise.create');

       } catch (\Exception $e) {
           $this->alert('error', 'Erreur lors de l\'ajout: ' . $e->getMessage());
       }
   }

   public function terminer()
   {
       if (!session()->has('selected_plan')) {
           return redirect()->route('plans.selection');
       }

       return redirect()->route('instance.create');
   }

   public function render()
   {
       return view('livewire.create-entreprise', [
           'nextStep' => $this->showTerminerButton ? 'plans' : 'entreprise'
       ])->layout('layouts.main');
   }
}
