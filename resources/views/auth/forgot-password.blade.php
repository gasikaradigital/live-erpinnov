<x-guest-layout>
    {{-- <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Email Password Reset Link') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card> --}}

    <div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
          <div class="authentication-inner py-6">
            <!-- Forgot Password -->
            <div class="card">
              <div class="card-body">
                <!-- Logo -->
                <div class="app-brand justify-content-center mb-6">
                <a href="/" class="app-brand-link gap-2">
                    <img src="{{ asset('assets/img/logo/logo.png') }}"
                    alt="Logo ERP INNOV"
                    style="width: 40px; height: 40px;"
                    class="img-fluid">
                    <span class="app-brand-text demo text-body fw-bold ms-1">ERP INNOV</span>
                </a>
                </div>
                <!-- /Logo -->
                <h4 class="mb-1 mt-3">Mot de passe oublié? 🔒</h4>
                <x-validation-errors class="mb-4" />
                <p class="mb-6">Entrez votre email et nous vous enverrons des instructions pour réinitialiser votre mot de passe.</p>
                @session('status')
                    <div class="alert alert-success" role="alert">
                        {{ $value }}
                    </div>
                @endsession
                <form method="POST" action="{{ route('password.email') }}" class="mb-6 fv-plugins-bootstrap5 fv-plugins-framework">
                    @csrf
                    <div class="mb-6 fv-plugins-icon-container">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input type="email" id="email" class="form-control" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Entrez votre email">
                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                 </div>
                  <button type="submit" class="btn btn-primary d-grid w-100 waves-effect waves-light mt-3">{{ __('Email Password Reset Link') }}</button>
                </form>
                <div class="text-center mt-3">
                  <a href="/login" class="d-flex justify-content-center">
                    <i class="ti ti-chevron-left scaleX-n1-rtl me-1_5"></i>
                    Retour à la connexion
                  </a>
                </div>
              </div>
            </div>
            <!-- /Forgot Password -->
          </div>
        </div>
      </div>
</x-guest-layout>
