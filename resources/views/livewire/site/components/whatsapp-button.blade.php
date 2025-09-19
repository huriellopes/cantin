@assets
<style>
    .whatsapp-button i {
        font-size: 1.5rem;
        color: #ffffff;
    }

    .whatsapp-button {
        border: none;
        position: fixed;
        bottom: 20px;
        right: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background-color: #25D366;
        border-radius: 50%;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        z-index: 1000;
    }

    .whatsapp-button:hover {
        background-color: #1DA851;
        transition: ease-in-out 0.3s;
    }
</style>
@endassets
<div>
    <button
        type="button"
        wire:click.prevent="openWhatsapp"
        class="whatsapp-button"
        name="Entre em contato com nosso WhatsApp"
        title="Entre em contato com nosso WhatsApp"
    >
        <i class="bi bi-whatsapp"></i>
    </button>
</div>
