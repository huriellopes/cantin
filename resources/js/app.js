import './bootstrap';

import mask from '@alpinejs/mask';
import focus from '@alpinejs/focus';

// O Livewire 4 já fornece e inicia o Alpine. Apenas registramos os plugins
// no Alpine dele (não importamos/iniciamos um segundo Alpine, o que causaria conflito).
document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(mask);
    window.Alpine.plugin(focus);
});
