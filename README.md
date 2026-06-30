<div align="center">

# 🏵️ CaNTIn

### Cadastro Nacional de Terreiros Inclusivos

**Mapeando e dando visibilidade aos terreiros que acolhem, respeitam e valorizam a transgeneridade.**

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6?logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-06B6D4?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-4169E1?logo=postgresql&logoColor=white)](https://postgresql.org)
[![CI](https://img.shields.io/badge/CI-Pest%20·%20Pint%20·%20PHPStan-22C55E)]()

</div>

---

## ✨ Sobre

O **CaNTIn** é uma iniciativa pioneira idealizada pelo Babalorixá **Alan de Ogun (Ogundeje)** para mapear e tornar acessíveis os terreiros que acolhem pessoas **trans, travestis e não-binárias** nas religiões de matriz africana. Conecta pessoas a espaços religiosos inclusivos em todo o Brasil e dá visibilidade a sacerdotes/sacerdotisas trans e às práticas inclusivas já existentes.

## 🚀 Stack

| Camada | Tecnologia |
|---|---|
| **Backend** | PHP 8.4 · Laravel 13 |
| **Frontend** | Livewire 4 · Alpine.js · Tailwind CSS 4 (Vite) |
| **Banco** | PostgreSQL 15 |
| **Infra** | Docker · Laravel Sail · Nginx |
| **Qualidade** | Pest · Pint · PHPStan/Larastan · Rector |
| **i18n** | 🇧🇷 pt_BR · 🇺🇸 en |

> O painel administrativo é 100% Livewire + Tailwind (sem Filament), com dashboard, gráficos, ações com confirmação e exportações.

## 🧩 Funcionalidades

- 🗺️ **Mapeamento** de terreiros, entidades parceiras e pessoas trans
- 📝 **Blog** com posts, categorias e comentários
- 🔎 **Busca** de terreiros com filtros e endereço (ViaCEP + BrasilAPI)
- 🔐 **Painel admin** com 15 CRUDs, papéis (super-admin/admin) e políticas
- 🍪 **Consentimento de cookies** (LGPD) com GA4/Ads carregados só após aceite
- 🌐 **Bilíngue** (pt_BR/en) com seletor de idioma
- 👤 **Perfil**: editar dados, trocar senha e excluir conta

## ⚙️ Desenvolvimento

O projeto requer **PHP 8.4** — caminho recomendado via **Laravel Sail** (Docker):

```bash
# subir containers (PHP 8.4 + PostgreSQL)
./vendor/bin/sail up -d

# dependências e ambiente
./vendor/bin/sail composer install
cp .env.example .env
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed

# assets
npm install && npm run dev   # ou: npm run build
```

> Defina no `.env` as credenciais de admin do seed (`SEED_SUPER_*`, `SEED_ADMIN_*`)
> e os tokens de integração (Telegram, Google Maps, `GA_MEASUREMENT_ID`). Nunca commite segredos.

### 🧪 Qualidade

```bash
./vendor/bin/sail pest        # testes
./vendor/bin/sail bin pint    # formatação (PSR + regras do pint.json)
./vendor/bin/sail bin phpstan # análise estática
./vendor/bin/sail bin rector  # refatoração assistida
```

## 🔄 CI/CD

- **CI** automático em cada PR e push: Pint + PHPStan + Pest + build Vite.
- **Branch `main` protegida**: exige PR e CI verde (sem commits diretos).
- **Release & Deploy automáticos**: ao mesclar na `main`, o sistema corta a próxima versão (bump de patch), publica o GitHub Release e faz o **deploy de produção** via SSH.

```
PR ──► CI ✅ ──► merge na main ──► release (vX.Y.Z) ──► deploy 🚀
```

## 🌐 Internacionalização

Arquivos em `lang/{pt_BR,en}/*.php` (+ `lang/*.json`). O idioma é resolvido por sessão (middleware `SetLocale`) e alternado pelo seletor disponível no site e no painel.

## 🔒 Segurança

- HTTPS (Let's Encrypt) com renovação automática.
- Segredos apenas em variáveis de ambiente / GitHub Secrets.
- Consentimento de cookies antes de qualquer rastreamento de análise/publicidade.

## 📄 Licença

Projeto proprietário. Todos os direitos reservados.

---

<div align="center">
Desenvolvido pela Empresa <strong>Hurvion Systems</strong>
</div>
