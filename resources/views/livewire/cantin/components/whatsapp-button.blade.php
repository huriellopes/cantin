<div>
    <style>
        .whatsapp-button {
            cursor: pointer;
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background-color: #25D366; /* Cor do WhatsApp */
            border-radius: 50%;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            z-index: 1000; /* Garante que o botão fique acima de outros elementos */
        }

        .whatsapp-button img {
            width: 40px;
            height: 40px;
        }

        .whatsapp-button:hover {
            background-color: #1DA851; /* Cor do WhatsApp mais escura no hover */
        }
    </style>

    <a href="" target="_blank" wire:click="openWhatsapp" class="whatsapp-button">
        <i class="bi bi-whatsapp" style="font-size: 2em"></i>
{{--        <img src="https://cdn-icons-png.flaticon.com/512/3536/3536705.png" alt="WhatsApp">--}}
    </a>
</div>
