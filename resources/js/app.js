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

Alpine.start();
