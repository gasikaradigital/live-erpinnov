{{-- resources/views/livewire/payment/forms/Mvola.blade.php --}}
<form wire:submit.prevent="processPayment" class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Numéro Mvola</label>
        <input type="text" wire:model.defer="mobileNumber" placeholder="034 XX XXX XX" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @error('mobileNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
        Payer {{ number_format($this->calculateTotal(), 2) }}€
    </button>
</form>
