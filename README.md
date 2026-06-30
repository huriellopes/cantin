# CaNTIn

> O projeto foi idealizado pelo sacerdote Babalorixá Alan de Ogun (Ogundeje), é uma iniciativa pioneira e transformadora, criada para mapear e tornar acessíveis os terreiros que acolhem, respeitam e valorizam a transgeneridade em sua totalidade. Com o objetivo de conectar pessoas trans a espaços religiosos inclusivos em todo o Brasil, o CaNTIn também realiza um mapeamento nacional, promovendo visibilidade para sacerdotes trans e para as práticas inclusivas já existentes nesses locais.

## Stack

- PHP 8.4+
- Laravel 13
- Livewire 4 + Alpine.js
- Tailwind CSS 4 (Vite)
- PostgreSQL
- Laravel Sail (Docker)

> O painel administrativo é construído em Livewire + Tailwind (sem Filament).

## Desenvolvimento

O projeto requer **PHP 8.4**. O caminho recomendado é via **Laravel Sail** (Docker):

```bash
# subir os containers (app em PHP 8.4 + PostgreSQL)
./vendor/bin/sail up -d

# dependências e ambiente
./vendor/bin/sail composer install
cp .env.example .env
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed

# assets
npm install
npm run dev   # ou: npm run build
```

### Comandos úteis

```bash
./vendor/bin/sail pest        # testes
./vendor/bin/sail bin pint    # formatação de código
./vendor/bin/sail bin rector  # análise/refatoração
```

> Defina no `.env` as credenciais de admin do seed (`SEED_SUPER_*`, `SEED_ADMIN_*`)
> e os tokens de integração (Telegram, Google Maps). Nunca commite segredos.

## Licença

> Todos os direitos reservados.
