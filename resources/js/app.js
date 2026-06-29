import './bootstrap';

import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';

Alpine.plugin(mask)

window.Alpine = Alpine;

document.addEventListener('livewire:load', function () {
    Livewire.on('loading', () => {
        document.querySelector('.spinner').classList.add('active')
    });

    Livewire.on('dadosSalvos', () => {
        document.querySelector('.spinner').classList.remove('active');
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');

    if (! loginForm || ! emailInput || ! passwordInput) {
        return;
    }

    loginForm.addEventListener('submit', function(event) {
        event.preventDefault(); // Impede o envio padrão do formulário
        event.stopPropagation(); // Impede a propagação do evento

        let isValid = true;

        // Validação do Email
        if (!emailInput.value.includes('@') || !emailInput.value.includes('.')) {
            emailInput.classList.add('is-invalid');
            emailError.textContent = 'Por favor, insira um email válido.';
            isValid = false;
        } else {
            emailInput.classList.remove('is-invalid');
        }

        // Validação da Senha
        if (passwordInput.value.length < 6) {
            passwordInput.classList.add('is-invalid');
            passwordError.textContent = 'A senha deve ter pelo menos 6 caracteres.';
            isValid = false;
        } else {
            passwordInput.classList.remove('is-invalid');
        }

        if (isValid) {
            // Se tudo estiver válido, você enviaria o formulário via AJAX
            // ou redirecionaria o usuário.
            alert('Formulário válido! Enviando dados...');
            // loginForm.submit(); // Se fosse um envio tradicional
        }
    });

    emailInput.addEventListener('input', function() {
        if (emailInput.classList.contains('is-invalid')) {
            emailInput.classList.remove('is-invalid');
        }
    });

    passwordInput.addEventListener('input', function() {
        if (passwordInput.classList.contains('is-invalid')) {
            passwordInput.classList.remove('is-invalid');
        }
    });
});

Alpine.start();
