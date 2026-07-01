<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Proxies confiáveis. A aplicação só é acessível através dos nossos
     * proxies (o Nginx Proxy Manager publica 80/443; os containers app/nginx
     * não expõem portas no host), então confiamos em toda a cadeia para ler o
     * IP real do visitante via X-Forwarded-For — corrige o ip_address das
     * visitas e a chave do rate limit de login (que antes pegavam o IP interno
     * do proxy). Cobre também a Cloudflare, caso o proxy dela seja ativado.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
