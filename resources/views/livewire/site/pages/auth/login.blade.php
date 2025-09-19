<div class="container mt-5 mb-4">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
        }

        .login-card-wrapper {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
            overflow: hidden;
        }

        .image-col {
            background-image: url({{ $image }});
            background-size: cover;
            background-position: center;
            min-height: 400px;
            border-top-left-radius: 15px;
            border-bottom-left-radius: 15px;
        }
        .form-col {
            padding: 40px;
        }
        .card-title {
            font-size: 2.2rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 25px;
            text-align: center;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(106, 17, 203, 0.25);
            border-color: #6a11cb;
        }
        /* Estilos para validação */
        .form-control.is-invalid {
            border-color: #dc3545; /* Cor da borda vermelha para erro */
            padding-right: calc(1.5em + 0.75rem); /* Espaço para ícone de erro, se usar */
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e"); /* Ícone de erro */
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        .invalid-feedback {
            color: #dc3545; /* Cor do texto da mensagem de erro */
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .btn-primary {
            border-radius: 8px;
            padding: 12px 0;
            font-weight: bold;
            background: linear-gradient(45deg, #6a11cb 0%, #2575fc 100%);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .text-muted a {
            color: #6a11cb;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .text-muted a:hover {
            color: #2575fc;
            text-decoration: underline;
        }
        .input-group-text {
            border-radius: 8px 0 0 8px;
            background-color: #f8f9fa;
            border: 1px solid #ddd;
            border-right: none;
            color: #6c757d;
        }

        @media (max-width: 767.98px) {
            .image-col {
                border-radius: 15px 15px 0 0;
                min-height: 200px;
            }
            .form-col {
                padding: 30px;
            }
            .login-card-wrapper {
                border-radius: 15px;
            }
        }

        .flipper-container {
            perspective: 1000px;
            /* Adicione estas duas linhas para que o container tenha uma altura base */
            min-height: 400px; /* Ou o valor que for apropriado para a sua UI */
            display: flex;
        }

        .flipper {
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.6s;
            width: 100%; /* Garante que o flipper ocupe todo o espaço */
        }

        .flipper.flipped {
            transform: rotateY(180deg);
        }

        .front, .back {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            border-radius: 15px;
        }

        .back {
            transform: rotateY(180deg);
        }

        .login-card-wrapper {
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
            overflow: hidden;
            width: 100%;
            height: 100%;
        }
    </style>
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="flipper-container">
                <div class="flipper @if(!$showLogin) flipped @endif">
                    <div class="front">
                        <div class="row login-card-wrapper">
                            <div class="col-md-6 d-none d-md-block image-col"></div>
                            <div class="col-md-6 form-col">
                                <h2 class="card-title">Faça seu Login</h2>
                                @error('message')
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="bi bi-exclamation-triangle-fill"></i>
                                            <span>{{ $message }}</span>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @enderror
                                <form method="POST" action="{{ route('site.auth.login.post') }}" enctype="application/x-www-form-urlencoded" class="needs-validation" novalidate>
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label visually-hidden">Email</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text @error('email') border-danger @enderror">
                                                <i class="bi bi-person-fill @error('email') text-danger @enderror"></i>
                                            </span>
                                            <input type="email" class="form-control @error('email') border-danger @enderror" id="email" name="email" placeholder="Seu email" value="{{ old('email') }}" required autofocus />
                                        </div>
                                        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label visually-hidden">Senha</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text @error('password') border-danger @enderror">
                                                <i class="bi bi-lock-fill @error('password') text-danger @enderror"></i>
                                            </span>
                                            <input type="password" class="form-control @error('password') border-danger @enderror" id="password" name="password" placeholder="Sua senha" required>
                                        </div>
                                        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="d-grid gap-2 mb-3">
                                        <button type="submit" class="btn btn-primary">Entrar</button>
                                    </div>
                                    <div class="text-center mt-3 text-muted">
                                        Não tem uma conta? <a wire:click.prevent="toggleForm" style="cursor: pointer; text-decoration: underline; color: #007bff;">Cadastre-se</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="back">
                        <div class="row login-card-wrapper">
                            <div class="col-md-6 d-none d-md-block image-col"></div>
                            <div class="col-md-6 form-col">
                                <h2 class="card-title">Crie sua Conta</h2>
                                <livewire:site.pages.auth.register />
                                <div class="text-center mt-3 text-muted">
                                    Já tem uma conta? <a wire:click.prevent="toggleForm" style="cursor: pointer; text-decoration: underline; color: #007bff;">Faça Login</a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
