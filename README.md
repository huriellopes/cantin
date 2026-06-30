<div align="center">

# 🏵️ CaNTIn

### Cadastro Nacional de Terreiros Inclusivos

**Mapeando e dando visibilidade aos terreiros que acolhem, respeitam e valorizam a transgeneridade.**

[![Laravel](https://img.shields.io/badge/Laravel-13-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?logo=php&logoColor=white)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-4-4E56A6?logo=livewire&logoColor=white)](https://livewire.laravel.com)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind-4-06B6D4?logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-4169E1?logo=postgresql&logoColor=white)](https://postgresql.org)
[![CI](https://img.shields.io/badge/CI-Pint%20·%20PHPStan%20·%20Rector%20·%20Pest-22C55E)]()

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
| **Fila** | Driver `database` (worker via Supervisor) |
| **Infra** | Docker · Laravel Sail · Nginx · HTTPS (Let's Encrypt) |
| **Qualidade** | Pest · Pint · PHPStan/Larastan · Rector |
| **i18n** | 🇧🇷 pt_BR · 🇺🇸 en |

> O painel administrativo é 100% Livewire + Tailwind (sem Filament), com dashboard, gráficos, ações com confirmação e exportações.

## 🧩 Funcionalidades

- 🗺️ **Mapeamento** de terreiros, entidades parceiras e pessoas trans
- 📝 **Blog** com posts, categorias e comentários
- 🔎 **Busca de CEP** (ViaCEP + BrasilAPI) com estados/cidades oficiais do **IBGE**
- 🔐 **Painel admin** com 15 CRUDs, papéis (super-admin/admin) e políticas
- 📊 **Tabela reutilizável**: busca, itens por página (10–100/todos) e ordenação por coluna, com paginação reativa
- 📤 **Exportações `.xlsx` via fila** com download no painel (toaster + link) e auto-exclusão do arquivo após baixar
- 🕵️ **Impersonate** (somente super-admin) com banner flutuante e **auditoria** completa
- 🔔 **Alertas no Telegram** para todos os erros (tópico de fórum, com throttling)
- 🍪 **Consentimento de cookies** (LGPD) com GA4/Ads carregados só após aceite
- 🌐 **Bilíngue** (pt_BR/en) com seletor de idioma reativo
- 👤 **Perfil**: editar dados, trocar senha e excluir conta (com confirmação por senha)

## ⚙️ Desenvolvimento

O projeto requer **PHP 8.4** — caminho recomendado via **Laravel Sail** (Docker):

```bash
# subir containers (PHP 8.4 + PostgreSQL)
./vendor/bin/sail up -d

# dependências e ambiente
./vendor/bin/sail composer install
cp .env.example .env
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed   # estados/cidades vêm do IBGE

# assets
npm install && npm run dev   # ou: npm run build

# fila (exportações)
./vendor/bin/sail artisan queue:work
```

> Defina no `.env` as credenciais de admin do seed (`SEED_SUPER_*`, `SEED_ADMIN_*`),
> os tokens do Telegram (`TELEGRAM_BOT_TOKEN`, `TELEGRAM_CHAT_ID`, `TELEGRAM_THREAD_ALERTS`)
> e o `GA_MEASUREMENT_ID`. Nunca commite segredos.

### 🧪 Qualidade

```bash
./vendor/bin/sail bin pint    # formatação (PSR + regras do pint.json)
./vendor/bin/sail bin phpstan # análise estática (Larastan)
./vendor/bin/sail bin rector  # integridade/refatoração (rector process)
./vendor/bin/sail pest        # testes
```

### 🗺️ Comandos úteis

```bash
./vendor/bin/sail artisan localidades:sync   # sincroniza estados/cidades do IBGE
./vendor/bin/sail artisan telegram:test      # envia um alerta de teste ao Telegram
```

## 🌿 Fluxo de branches

- Novas branches **partem da `dev`**; Pull Requests são abertos **para a `dev`**.
- Quando a `dev` está pronta para produção, abre-se um PR **`dev → main`**.
- `main` (produção) e `dev` são **protegidas**: exigem PR + CI verde (sem push direto).

```
feature (da dev) ──► PR p/ dev ──► CI ✅ ──► merge na dev
dev ──► PR p/ main ──► merge ──► release + deploy 🚀
```

## 🔄 CI/CD

- **CI** em cada PR e push (`main`/`dev`): **Pint + PHPStan + Rector (dry-run) + Pest + build Vite**.
- **Release & Deploy automáticos**: ao mesclar na `main`, o sistema corta a próxima versão (bump de patch), publica o GitHub Release e faz o **deploy de produção** via SSH. A versão atual aparece no rodapé do painel.

## 🌐 Internacionalização

Arquivos em `lang/{pt_BR,en}/*.php` (+ `lang/*.json`). O idioma é resolvido por sessão (middleware `SetLocale`) e alternado pelo seletor disponível no site e no painel.

## 🔒 Segurança

- HTTPS (Let's Encrypt) com renovação automática.
- Segredos apenas em variáveis de ambiente / GitHub Secrets.
- Consentimento de cookies antes de qualquer rastreamento de análise/publicidade.
- Impersonate restrito a super-admin e auditado.

## 📄 Licença

Projeto proprietário. Todos os direitos reservados.

---

<div align="center">
Desenvolvido pela Empresa <strong>Hurvion Systems</strong>
</div>
