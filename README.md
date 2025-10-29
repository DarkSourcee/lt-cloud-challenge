# LT Cloud - Developers & Articles Management System

Uma mini-aplicação responsiva desenvolvida em **Laravel + Livewire** para gerenciar Desenvolvedores e Artigos com relacionamento muitos-para-muitos.

## 🚀 Funcionalidades

### Autenticação
- Sistema completo de autenticação com Laravel Breeze
- Login, registro e recuperação de senha
- Proteção de rotas com middleware auth

### Desenvolvedores (CRUD Completo)
- **Criar, Editar, Visualizar e Deletar** desenvolvedores
- Campos: nome, e-mail único, senioridade (Jr/Pl/Sr), skills (tags)
- **Pesquisa e filtros em tempo real** (Livewire):
  - Busca por nome ou e-mail
  - Filtro por skill
  - Filtro por senioridade
- Badge com contagem de artigos por desenvolvedor
- Layout **responsivo** em grid (desktop) e lista (mobile)

### Artigos (CRUD Completo)
- **Criar, Editar, Visualizar e Deletar** artigos
- Campos: título, slug, conteúdo (HTML), imagem de capa opcional, data de publicação
- **Upload de imagem de capa**
- Vincular múltiplos desenvolvedores ao criar/editar
- Badge com contagem de desenvolvedores por artigo
- Layout **responsivo** em grid (desktop) e lista (mobile)

### Segurança & Autorização
- **Policies** implementadas para garantir que cada usuário enxergue apenas seus próprios dados
- Validações de formulário
- Proteção CSRF

### UX & Design
- Interface moderna e responsiva com **Tailwind CSS**
- **Tema claro/escuro** persistente (já implementado no Laravel Breeze)
- Animações e transições suaves
- Grid card-based para listagens
- Modais de confirmação para exclusões

### Dados Fake
- **Seeders** com Faker para popular o banco com dados de demonstração
- **Factories** para criar dados de teste facilmente

## 📋 Requisitos

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/PostgreSQL/SQLite
- Extensões PHP: fileinfo, mbstring, pdo

## 🔧 Instalação

### 1. Clone o repositório

```bash
git clone <seu-repositorio>
cd lt-cloud
```

### 2. Instale as dependências PHP

```bash
composer install --ignore-platform-reqs
```

### 3. Configure o ambiente

Copie o arquivo `.env.example` para `.env`:

```bash
copy .env.example .env  # Windows
# ou
cp .env.example .env    # Linux/Mac
```

Configure as variáveis de ambiente no arquivo `.env`:

```env
APP_NAME="LT Cloud"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lt_cloud
DB_USERNAME=root
DB_PASSWORD=

# Para SQLite (alternativa simples):
# DB_CONNECTION=sqlite
# Crie o arquivo: touch database/database.sqlite
```

### 4. Gere a chave da aplicação

```bash
php artisan key:generate
```

### 5. Crie o banco de dados

Crie um banco de dados MySQL chamado `lt_cloud` ou use SQLite.

### 6. Execute as migrações e seeders

```bash
php artisan migrate --seed
```

Isso criará:
- 11 usuários (1 demo + 10 aleatórios)
- 2-5 desenvolvedores por usuário
- 3-8 artigos por usuário
- Relacionamentos entre artigos e desenvolvedores

### 7. Crie o link simbólico para storage

```bash
php artisan storage:link
```

### 8. Instale as dependências do Node.js

```bash
npm install
```

### 9. Compile os assets

Para desenvolvimento:
```bash
npm run dev
```

Para produção:
```bash
npm run build
```

### 10. Inicie o servidor

```bash
php artisan serve
```

Acesse: `http://localhost:8000`

## 🔑 Credenciais de Demonstração

Após executar `php artisan migrate --seed`:

**E-mail:** demo@ltcloud.com  
**Senha:** password

## 📱 Estrutura do Projeto

```
lt-cloud/
├── app/
│   ├── Livewire/
│   │   ├── Articles/
│   │   │   ├── Index.php      # Listagem de artigos
│   │   │   ├── Create.php     # Criar artigo
│   │   │   └── Edit.php       # Editar artigo
│   │   └── Developers/
│   │       ├── Index.php      # Listagem de desenvolvedores
│   │       ├── Create.php     # Criar desenvolvedor
│   │       └── Edit.php       # Editar desenvolvedor
│   ├── Models/
│   │   ├── Article.php        # Model Article
│   │   ├── Developer.php      # Model Developer
│   │   └── User.php           # Model User
│   └── Policies/
│       ├── ArticlePolicy.php  # Policy para artigos
│       └── DeveloperPolicy.php # Policy para desenvolvedores
├── database/
│   ├── factories/
│   │   ├── ArticleFactory.php
│   │   └── DeveloperFactory.php
│   ├── migrations/
│   │   ├── *_create_developers_table.php
│   │   ├── *_create_articles_table.php
│   │   └── *_create_article_developer_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── livewire/
│       │   ├── articles/      # Views dos artigos
│       │   └── developers/    # Views dos desenvolvedores
│       └── dashboard.blade.php
└── routes/
    └── web.php
```

## 🎨 Tecnologias Utilizadas

- **Laravel 12** - Framework PHP
- **Livewire 3** - Framework para componentes dinâmicos
- **Tailwind CSS** - Framework CSS
- **Laravel Breeze** - Autenticação
- **MySQL/SQLite** - Banco de dados
- **Faker** - Geração de dados fake

## 📸 Funcionalidades Detalhadas

### Dashboard
- Cartões com contagem de desenvolvedores e artigos
- Ações rápidas para criar novos registros
- Design responsivo e atraente

### Desenvolvedores
- **Listagem:** Grid responsivo com cards contendo nome, e-mail, senioridade, skills e contagem de artigos
- **Filtros:** Busca em tempo real, filtro por skill e senioridade
- **Formulário:** Adicionar skills dinamicamente com tags
- **Validações:** E-mail único, campos obrigatórios

### Artigos
- **Listagem:** Grid responsivo com cards mostrando título, preview do conteúdo, imagem de capa e contagem de desenvolvedores
- **Formulário:** Editor de conteúdo HTML, upload de imagem, seleção de múltiplos desenvolvedores
- **Status:** Artigos podem ser publicados (com data) ou salvos como rascunho

## 🛡️ Segurança

- **Policies:** Usuários só podem visualizar, editar e deletar seus próprios dados
- **Validação:** Todos os formulários têm validação server-side
- **Autenticação:** Rotas protegidas com middleware auth
- **CSRF Protection:** Tokens CSRF em todos os formulários

## 🎯 Boas Práticas Implementadas

✅ Separation of Concerns (Models, Controllers, Views)  
✅ Eloquent ORM com relacionamentos  
✅ Migrations versionadas  
✅ Factories e Seeders para testes  
✅ Policies para autorização  
✅ Validações robustas  
✅ UI/UX responsiva  
✅ Tema claro/escuro  
✅ Código limpo e bem documentado  

## 📦 Comandos Úteis

```bash
# Resetar banco e popular novamente
php artisan migrate:fresh --seed

# Criar mais dados fake manualmente
php artisan tinker
>>> \App\Models\Developer::factory(10)->create(['user_id' => 1]);

# Limpar cache
php artisan optimize:clear

# Rodar testes (se implementados)
php artisan test
```

## 🚢 Deploy (Opcional)

### Opções de Deploy Gratuito:
- **Railway.app**
- **Render.com**
- **Fly.io**
- **Heroku** (com add-on MySQL)

### Configuração para Produção:

1. Ajuste o `.env`:
```env
APP_ENV=production
APP_DEBUG=false
```

2. Configure o banco de dados de produção

3. Execute:
```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan optimize
npm run build
```

## 📧 Contato

Para dúvidas ou sugestões, entre em contato:  
**CONTATO@LTCLOUD.COM.BR**

---

Desenvolvido com ❤️ para o desafio LT Cloud
