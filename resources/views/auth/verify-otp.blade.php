{{-- resources/views/auth/verify-otp.blade.php --}}
<x-main-layout>
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Vérification de votre compte
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Un code de vérification a été envoyé à votre adresse email
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                @if (session('status'))
                    <div class="mb-4 text-sm text-green-600 bg-green-100 rounded-lg p-4">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 text-sm text-red-600 bg-red-100 rounded-lg p-4">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('verification.verify') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="otp" class="block text-sm font-medium text-gray-700">
                            Code de vérification
                        </label>
                        <div class="mt-1">
                            <input type="text"
                                   name="otp"
                                   id="otp"
                                   required
                                   value="{{ old('otp') }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm"
                                   placeholder="Entrez le code à 6 chiffres">
                        </div>
                        @error('otp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Vérifier
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <form action="{{ route('verification.resend') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                            Renvoyer le code
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-main-layout>
