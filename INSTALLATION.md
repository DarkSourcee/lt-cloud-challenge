# Guia de Instalação Rápida - LT Cloud

## Passos Rápidos para Instalação

### 1. Instalar Dependências
```bash
composer install --ignore-platform-reqs
npm install
```

### 2. Configurar Ambiente
```bash
# Copiar arquivo de configuração
copy .env.example .env  # Windows
# ou
cp .env.example .env    # Linux/Mac

# Gerar chave da aplicação
php artisan key:generate
```

### 3. Configurar Banco de Dados no .env

**Opção 1: MySQL**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lt_cloud
DB_USERNAME=root
DB_PASSWORD=
```

**Opção 2: SQLite (mais fácil)**
```env
DB_CONNECTION=sqlite
```
Depois execute: `touch database/database.sqlite` (Linux/Mac) ou crie o arquivo manualmente no Windows

### 4. Executar Migrações e Seeders
```bash
php artisan migrate --seed
php artisan storage:link
```

### 5. Compilar Assets
```bash
npm run dev  # Desenvolvimento
# ou
npm run build  # Produção
```

### 6. Iniciar Servidor
```bash
php artisan serve
```

Acesse: **http://localhost:8000**

## Credenciais Demo

**E-mail:** demo@ltcloud.com  
**Senha:** password

## Estrutura Criada

Após o seed, você terá:
- ✅ 11 usuários (1 demo + 10 aleatórios)
- ✅ 2-5 desenvolvedores por usuário
- ✅ 3-8 artigos por usuário
- ✅ Relacionamentos configurados

## Troubleshooting

### Erro de extensão fileinfo
Use: `composer install --ignore-platform-reqs`

### Erro de permissões (Linux/Mac)
```bash
chmod -R 775 storage bootstrap/cache
```

### Resetar banco de dados
```bash
php artisan migrate:fresh --seed
```

### Limpar cache
```bash
php artisan optimize:clear
```

## Recursos Implementados

✅ **Autenticação** - Laravel Breeze com Livewire  
✅ **CRUD Desenvolvedores** - Com filtros em tempo real  
✅ **CRUD Artigos** - Com upload de imagem  
✅ **Relacionamento N:N** - Entre desenvolvedores e artigos  
✅ **Policies** - Isolamento de dados por usuário  
✅ **UI Responsiva** - Tailwind CSS  
✅ **Tema Claro/Escuro** - Persistente  
✅ **Seeders com Faker** - Dados de demonstração  

## Próximos Passos

1. Faça login com as credenciais demo
2. Explore o Dashboard
3. Crie desenvolvedores
4. Crie artigos e associe desenvolvedores
5. Teste os filtros e buscas
6. Alterne entre tema claro e escuro

## Suporte

Para dúvidas: **CONTATO@LTCLOUD.COM.BR**

