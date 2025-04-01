# 🏀 Project-BallDontLie

## 📌 Sobre o Projeto
Project-BallDontLie é uma aplicação baseada em Laravel que gerencia informações sobre times, jogadores e jogos de basquete. O sistema permite operações CRUD e sincronização de dados via API externa.

## 🛠 Requisitos
- **Sistema operacional**: Windows 11
- **Tecnologias utilizadas**:
  - Laravel 10
  - PHP 8.2
  - MySQL
  - Laravel Sanctum
  - Docker

## ⚙️ Instalação
### 1. Instalando o Docker no Windows
Se ainda não possui o Docker instalado, siga os passos:
- Baixe o [Docker Desktop](https://www.docker.com/products/docker-desktop/).
- Instale e reinicie o sistema, caso necessário.
- Certifique-se de que a virtualização está ativada no BIOS.

### 2. Configurando o ambiente Laravel
🔹 **Obs:** O Docker deve estar em execução no Windows antes de prosseguir.

#### 🚀 Subindo os containers com Docker
```sh
docker-compose up -d --build
```

#### 📦 Instalando dependências do Laravel
```sh
docker exec -it php composer install
```

#### 🔑 Gerando chave da aplicação
```sh
docker exec -it php php artisan key:generate
```

#### 🗂 Criando as tabelas no banco de dados
```sh
docker exec -it php php artisan migrate
```

#### 🗂 Populando o banco de dados
```sh
docker exec -it php php artisan db:seed
```

## 🔐 Credenciais de Acesso
### Admin
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

### Usuário Padrão
```json
{
    "email": "user@example.com",
    "password": "password"
}
```

## 🔄 Sincronizando Dados com a API
Execute os comandos abaixo para sincronizar os dados de times, jogadores e jogos:
```sh
docker exec -it php php artisan app:sync-teams-from-api
docker exec -it php php artisan app:sync-players-from-api
docker exec -it php php artisan app:sync-games-from-api
```

## 🧪 Executando Testes
Para rodar os testes, utilize:
```sh
vendor/bin/phpunit --filter PlayerServiceTest
vendor/bin/phpunit --filter PlayerControllerTest
```

## 🚀 Funcionalidades
- ✅ Login
- 🚪 Logout
- 🏀 Criar jogadores
- ✏️ Editar jogadores
- 📋 Listar jogadores
- 🗑  Apagar jogadores

## 💻 Tecnologias utilizadas:
  - PHPUnit - Testes Unitários e de Controller
  - Está sendo utilizado, DTOs, Contract, Repository, Resource, Factory...


## 📌 Documentação das Rotas
As rotas da API estão disponíveis no Postman.

🔗 **Dica**: Para testar as rotas, utilize a extensão [Postman](https://www.postman.com/) ou o comando `curl` no terminal. [Json para importação](https://drive.google.com/file/d/1LqvkQAsUM_bbumrLo0142RtQjc24MwIh/view?usp=drive_link)


---
📝 **Dúvidas ou contribuições?** Sinta-se à vontade para abrir uma issue ou enviar um PR! 🚀

