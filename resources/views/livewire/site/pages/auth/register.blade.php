<div>
    <form wire:submit.prevent="store" class="needs-validation" novalidate>
        @csrf
        <div class="mb-3">
            <label for="register-name" class="form-label visually-hidden">Nome</label>
            <div class="input-group has-validation">
                <span class="input-group-text @error('name') border-danger @enderror">
                    <i class="bi bi-person-circle @error('name') text-danger @enderror"></i>
                </span>
                <input type="text" class="form-control @error('name') border-danger @enderror" id="register-name" wire:model="name" placeholder="Seu nome" />
            </div>
        </div>
        <div class="mb-3">
            <label for="register-email" class="form-label visually-hidden">Email</label>
            <div class="input-group has-validation">
                <span class="input-group-text @error('email') border-danger @enderror">
                    <i class="bi bi-envelope-fill @error('email') text-danger @enderror"></i>
                </span>
                <input type="email" class="form-control @error('email') border-danger @enderror" id="register-email" wire:model="email" placeholder="Seu email" />
            </div>
        </div>
        <div class="mb-3">
            <label for="register-password" class="form-label visually-hidden">Senha</label>
            <div class="input-group has-validation">
                <span class="input-group-text @error('password') border-danger @enderror">
                    <i class="bi bi-lock-fill @error('password') text-danger @enderror"></i>
                </span>
                <input type="password" class="form-control @error('password') border-danger @enderror" id="register-password" wire:model="password" placeholder="Sua senha" />
            </div>
        </div>
        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary" wire:target="store" wire:loading.attr="disabled">
                Registrar
                <span wire:loading wire:target="store" class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">{{ __('Loading...') }}</span>
                </span>
            </button>
        </div>
    </form>
</div>
