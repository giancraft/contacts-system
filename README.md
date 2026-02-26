# Sistema de Contatos

Sistema CRUD de pessoas e contatos desenvolvido em PHP puro com Doctrine ORM, padrão MVC, Docker e frontend em HTML/CSS/JS.

---

## Tecnologias

- **Back-end:** PHP 8.2 (sem framework)
- **ORM:** Doctrine ORM 2.x
- **Banco de dados:** MySQL 8.0
- **Front-end:** HTML5 + CSS3 + JavaScript (vanilla)
- **Container:** Docker + Docker Compose
- **Dependências:** Composer

---

## Estrutura do Projeto

```
contacts-system/
├── config/
│   └── database.php          # Configuração Doctrine / EntityManager
├── public/
│   ├── index.php             # Front controller (entry point)
│   ├── app.html              # SPA – interface do usuário
│   └── .htaccess             # Rewrite rules do Apache
├── src/
│   ├── Core/
│   │   ├── BaseController.php
│   │   └── Router.php
│   ├── Controller/
│   │   ├── PessoaController.php
│   │   └── ContatoController.php
│   ├── Entity/
│   │   ├── Pessoa.php
│   │   └── Contato.php
│   └── Repository/
│       ├── PessoaRepository.php
│       └── ContatoRepository.php
├── cli-config.php            # Doctrine CLI
├── composer.json
├── Dockerfile
└── docker-compose.yml
```

---

## Executando com Docker (recomendado)

### Pré-requisitos
- [Docker](https://www.docker.com/) instalado
- [Docker Compose](https://docs.docker.com/compose/) instalado

### Passo a passo

```bash
# 1. Clone o repositório
git clone <URL_DO_REPOSITORIO>
cd contacts-system

# 2. Suba os containers
docker compose up -d --build

# 3. Aguarde o banco de dados inicializar (cerca de 15s)
#    Você pode acompanhar com:
docker compose logs -f db

# 4. Instale as dependências
docker compose exec app composer install

# 4. Crie as tabelas via Doctrine Schema Tool
docker compose exec app php vendor/bin/doctrine orm:schema-tool:create

# 5. Acesse a aplicação
# http://localhost:8081
```

> **Para derrubar:** `docker compose down`  
> **Para apagar também os dados:** `docker compose down -v`

---

## Executando sem Docker

### Pré-requisitos
- PHP 8.2+ com extensões: `pdo`, `pdo_mysql`
- MySQL 8.0+
- Composer

### Passo a passo

```bash
# 1. Instale as dependências
composer install

# 2. Configure as variáveis de ambiente (ou edite config/database.php)
export DB_HOST=localhost
export DB_PORT=3306
export DB_NAME=contacts_db
export DB_USER=seu_usuario
export DB_PASS=sua_senha

# 3. Crie o banco de dados manualmente no MySQL
# CREATE DATABASE contacts_db;

# 4. Crie as tabelas
php vendor/bin/doctrine orm:schema-tool:create

# 5. Inicie o servidor embutido do PHP na pasta public/
php -S localhost:8080 -t public

# 6. Acesse: http://localhost:8081
```

---

## Comandos Doctrine úteis

```bash
# Ver SQL que será executado (sem aplicar)
php vendor/bin/doctrine orm:schema-tool:create --dump-sql

# Atualizar schema após alterar entidades
php vendor/bin/doctrine orm:schema-tool:update --force

# Validar mapeamento das entidades
php vendor/bin/doctrine orm:validate-schema
```

---

## Endpoints da API REST

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/pessoas` | Listar pessoas (aceita `?search=nome`) |
| GET | `/api/pessoas/{id}` | Buscar pessoa por ID |
| POST | `/api/pessoas` | Cadastrar pessoa |
| PUT | `/api/pessoas/{id}` | Atualizar pessoa |
| DELETE | `/api/pessoas/{id}` | Excluir pessoa |
| GET | `/api/contatos` | Listar contatos (aceita `?pessoaId=x`) |
| GET | `/api/contatos/{id}` | Buscar contato por ID |
| POST | `/api/contatos` | Cadastrar contato |
| PUT | `/api/contatos/{id}` | Atualizar contato |
| DELETE | `/api/contatos/{id}` | Excluir contato |

### Exemplo de payload – Pessoa
```json
{ "nome": "João Silva", "cpf": "12345678901" }
```

### Exemplo de payload – Contato
```json
{ "tipo": "email", "descricao": "joao@email.com", "idPessoa": 1 }
```
Tipos aceitos: `"telefone"` | `"email"`

---

## Funcionalidades

- ✅ RF01 – Consulta de pessoas com tabela paginada
- ✅ RF02 – Pesquisa por nome (busca dinâmica)
- ✅ RF03 – Consulta de contatos com filtro por pessoa
- ✅ RF04 – CRUD completo de pessoas (criar, visualizar, editar, excluir)
- ✅ RF05 – CRUD completo de contatos (criar, visualizar, editar, excluir)
