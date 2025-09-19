<div>
    <style>
        .footer-custom {
            width: 100%;
            background-color: #212529; /* Cor de fundo escura */
            color: white;
            padding: 40px 0;
            margin-top: auto; /* Para garantir que o footer vá para o final da página */
        }

        .footer-custom .logo-container {
            display: flex;
            align-items: center;
            justify-content: flex-start; /* Alinha a logo à esquerda */
        }

        .footer-custom .logo-container img {
            max-height: 80px;
        }

        /* Ajuste para as listas de links do footer */
        .footer-custom ul {
            list-style-type: none; /* Remove os marcadores de lista */
            padding-left: 0;
        }

        .footer-custom ul li a {
            color: white;
            text-decoration: none;
        }

        .footer-custom ul li a:hover {
            text-decoration: underline; /* Adiciona sublinhado ao passar o mouse */
        }

        .footer-custom .footer-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-custom .footer-info .infos {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            line-height: 0.5;
        }

        .footer-custom .footer-info .social-media {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
        }

        .footer-custom .footer-info .social-media a {
            color: white;
            font-size: 24px;
        }

        .footer-custom .footer-info .social-media a:hover {
            color: #007bff;
            transition: color 0.3s ease;
        }

        .copyright {
            display: flex;
            font-size: 14px;
            text-align: center;
            justify-content: center;
            align-items: center;
            width: 100%;
            color: #6c757d;
        }
    </style>
    <footer class="footer-custom mt-auto">
        <div class="container mt-4">
            <div class="row footer-info">
                <div class="col-md-4 col-12 text-center text-md-start mb-3 mb-md-0">
                    <img src="{{ $logo }}" alt="CaNTIn" title="CaNTIn" class="img-fluid" style="max-height: 80px;">
                </div>

                @if (!empty($static_pages))
                    <div class="col-md-4 col-12 text-center mb-3 mb-md-0">
                        <h5>Páginas Estáticas</h5>
                        <ul>
                            @foreach ($static_pages as $page)
                                <li>
                                    <a href="{{ route('site.static.page', $page->slug) }}">
                                        {{ str($page->name)->ucfirst() }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="col-md-4 col-12 infos">
                    <div>
                        <p>
                            <strong>Babalorixá Alan T'Ogun</strong>
                        </p>
                        <p>
                            <i class="bi bi-phone-fill"></i>
                            (61) 9 9977-6608
                        </p>
                        <p>
                            <i class="bi bi-envelope-at-fill"></i>
                            seggvg@gmail.com
                        </p>
                    </div>
                    <div class="social-media w-auto">
                        <a href="https://www.facebook.com/alan.baloni" target="_blank" class="text-decoration-none bg-blue">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://www.instagram.com/alanbaloni79/" target="_blank" class="text-decoration-none">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/prof-m-sc-jorge-alan-baloni-21932b299/" target="_blank" class="text-decoration-none">
                            <i class="bi bi-linkedin"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="row copyright mt-3">
                <div class="col-md-12 col-12 text-center w-auto info">
                    <span class="d-block mb-1">Desenvolvedor Huriel Lopes</span>
                </div>
            </div>
        </div>
    </footer>
</div>
