<div>
    <form method="POST" action="{{ route('site.auth.register.post') }}" class="needs-validation" novalidate>
        @csrf
        <div class="mb-3">
            <label for="register-name" class="form-label visually-hidden">Nome</label>
            <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                <input type="text" class="form-control" id="register-name" name="name" placeholder="Seu nome" required />
            </div>
        </div>
        <div class="mb-3">
            <label for="register-email" class="form-label visually-hidden">Email</label>
            <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                <input type="email" class="form-control" id="register-email" name="email" placeholder="Seu email" required />
            </div>
        </div>
        <div class="mb-3">
            <label for="register-password" class="form-label visually-hidden">Senha</label>
            <div class="input-group has-validation">
                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                <input type="password" class="form-control" id="register-password" name="password" placeholder="Sua senha" required />
            </div>
        </div>
        <div class="d-grid gap-2 mb-3">
            <button type="submit" class="btn btn-primary">Registrar</button>
        </div>
    </form>
</div>
