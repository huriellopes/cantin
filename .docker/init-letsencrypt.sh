#!/bin/sh
# Emissão inicial do certificado Let's Encrypt (rodar UMA vez no servidor).
#
#   cd /apps/cantin
#   ./.docker/init-letsencrypt.sh [email]
#
# Pré-requisitos: nginx já no ar (deploy concluído) servindo o desafio ACME em
# /.well-known/acme-challenge/ e DNS de cantinbr.com.br apontando para o servidor.
set -e

DOMAIN="cantinbr.com.br"
EMAIL="${1:-huriel@ramarventures.com}"
COMPOSE="docker compose -f docker-compose.prod.yml"

echo "==> Removendo certificado temporário (dummy), se existir"
rm -rf "./.docker/certbot/conf/live/$DOMAIN" \
       "./.docker/certbot/conf/archive/$DOMAIN" \
       "./.docker/certbot/conf/renewal/$DOMAIN.conf"

echo "==> Solicitando certificado real ao Let's Encrypt (webroot)"
$COMPOSE run --rm --entrypoint "" certbot \
  certbot certonly --webroot -w /var/www/certbot \
  -d "$DOMAIN" --email "$EMAIL" --agree-tos --no-eff-email --non-interactive

echo "==> Recarregando nginx com o certificado válido"
$COMPOSE exec -T nginx nginx -s reload || $COMPOSE restart nginx

echo "==> Pronto. HTTPS ativo para https://$DOMAIN"
