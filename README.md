## Tecnologias

- PHP 8.2+ + Laravel 11
- MySQL 8.0
- Docker + Nginx
- PHPUnit (SQLite :memory: para testes)

---

## Rodar via Docker

```bash
# 1. Copiar .env
cp .env.example .env

# 2. Build e subir containers
docker compose build
docker compose up -d
```

A API estará disponível em **http://localhost:8000**

### Rodar testes (Docker)

```bash
docker compose exec app php artisan test
```

---

## Rodar Local (sem Docker)

Requisitos: PHP 8.x com extensão SQLite, Composer, MySQL

```bash
# 1. Instalar dependências
composer install

# 2. Configurar .env
cp .env.example .env
# Editar DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD conforme seu MySQL local

# 3. Gerar key e rodar migrations
php artisan key:generate
php artisan migrate

# 4. Subir servidor
php artisan serve
```

A API estará disponível em **http://localhost:8000**

### Rodar testes (Local)

Os testes usam SQLite em memória.

```bash
php artisan test
```

---

## Rotas da API (v1)

Base: `http://localhost:8000/api/v1`

### Importação de XML

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/v1/import/hotels` | Importa hotels.xml |
| POST | `/api/v1/import/rooms` | Importa rooms.xml |
| POST | `/api/v1/import/rates` | Importa rates.xml |
| POST | `/api/v1/import/reservations` | Importa reservations.xml |

### CRUD de Quartos (JSON)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/v1/rooms` | Listar quartos |
| GET | `/api/v1/rooms/{id}` | Detalhe de um quarto |
| POST | `/api/v1/rooms` | Criar quarto |
| PUT | `/api/v1/rooms/{id}` | Atualizar quarto |
| DELETE | `/api/v1/rooms/{id}` | Remover quarto |

### Reservas (JSON)

| Método | Rota | Descrição |
|--------|------|-----------|
| GET | `/api/v1/reservations` | Listar reservas |
| POST | `/api/v1/reservations` | Criar reserva (valida disponibilidade) |

---

## Testar via Postman

Na pasta `docs/postman/` estão os arquivos prontos para importar:

- `api-laravel.postman_collection.json` — Collection com todas as rotas
- `api_gestao_hoteleira.postman_environment.json` — Environment com variáveis

---

## Testes Automatizados

| Suite | Arquivo | Cobertura |
|-------|---------|-----------|
| Unit | `ServicoReservaTest` | Regra de disponibilidade (adjacências, sobreposições) |
| Feature | `ImportacaoApiTest` | Importação dos 4 XMLs + verificação de registros + idempotência |
| Feature | `QuartoApiTest` | CRUD completo (criar, listar, buscar, atualizar, deletar, 404, 422) |
| Feature | `ReservaApiTest` | Criação + conflito de datas + listagem + validação room/hotel |
