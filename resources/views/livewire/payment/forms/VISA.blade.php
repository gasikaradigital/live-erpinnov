{{-- resources/views/livewire/payment/forms/VISA.blade.php --}}
<form wire:submit.prevent="processPayment"
class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700">Nom sur la carte</label>
        <input type="text" wire:model.defer="cardInfo.name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @error('cardInfo.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Numéro de carte</label>
        <input type="text" wire:model.defer="cardInfo.number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
        @error('cardInfo.number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700">Date d'expiration</label>
            <input type="text" wire:model.defer="cardInfo.expiry" placeholder="MM/YY" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('cardInfo.expiry') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">CVC</label>
            <input type="text" wire:model.defer="cardInfo.cvc" maxlength="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('cardInfo.cvc') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
    </div>
    <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg hover:bg-green-700">
        Payer {{ number_format($this->calculateTotal(), 2) }}€
    </button>
</form>
