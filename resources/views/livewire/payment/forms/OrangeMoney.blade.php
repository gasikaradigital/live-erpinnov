{{-- resources/views/livewire/payment/forms/OrangeMoney.blade.php --}}
<form wire:submit.prevent="processPayment"
class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Numéro Orange Money</label>
        <input type="text" wire:model.defer="mobileNumber" placeholder="032 XX XXX XX" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @error('mobileNumber') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>
    {{-- <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-lg hover:bg-orange-700">
        Payer {{ number_format($this->calculateTotal(), 2) }}€
    </button> --}}
</form>
