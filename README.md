# Ranking API

API em PHP puro para consultar rankings de recordes pessoais por movimento. O projeto usa uma estrutura simples inspirada em camadas MVC, com entrada HTTP em `public/index.php`, roteamento manual, controller, service, repositories e models conectados a MySQL via PDO.

## Requisitos

- PHP `>= 7.2`
- Composer
- MySQL
- Extensão PDO para MySQL habilitada no PHP

## Configuração do ambiente

1. Instale as dependências:

```bash
composer install
```

2. Crie o arquivo `.env` a partir do exemplo:

```bash
cp .env.example .env
```

3. Ajuste as variáveis de banco no `.env`:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ranking_api
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

## Migrações e seed

O projeto possui um script shell que:

- cria o banco `ranking_api` se ele não existir;
- cria as tabelas principais;
- executa os seeders em PHP.

O arquivo é [script/run_migrations.sh](/home/joaquim/desafios-projeto/ranking-api/script/run_migrations.sh).

No estado atual do repositório, ele já está com permissão de execução. Ainda assim, é uma boa prática manter esse lembrete no passo a passo, porque em outro ambiente, clone ou compactação de arquivos essa permissão pode não ser preservada. Se isso acontecer, rode:

```bash
chmod +x script/run_migrations.sh
```

Depois execute:

```bash
./script/run_migrations.sh
```

## Subindo o servidor

Para rodar a API localmente, use exatamente o comando abaixo a partir da raiz do projeto:

```bash
php -S localhost:8000 -t public
```

Esse comando publica o diretório `public` como document root e usa [public/index.php](/home/joaquim/desafios-projeto/ranking-api/public/index.php) como ponto de entrada da aplicação.

## Endpoint disponível

### `GET /api/ranking`

Retorna o ranking de um movimento por `movement_id` ou por `movement`.

Exemplos:

```bash
curl "http://localhost:8000/api/ranking?movement_id=1"
```

```bash
curl "http://localhost:8000/api/ranking?movement=Deadlift"
```

Exemplo de resposta:

```json
{
  "status": true,
  "data": {
    "movement": "Deadlift",
    "ranking": [
      {
        "position": 1,
        "user": "José",
        "record": 190,
        "date": "2021-01-06 00:00:00"
      }
    ]
  }
}
```

## Estrutura do projeto

```text
ranking-api/
├── app/
│   ├── Database/
│   │   ├── Connection.php
│   │   ├── Migrations/
│   │   └── Seeders/
│   ├── Http/
│   │   └── Controllers/
│   ├── Models/
│   ├── Repositories/
│   └── Services/
├── config/
├── public/
├── routers/
├── script/
├── storage/
├── vendor/
├── .env.example
├── composer.json
└── README.md
```

## Explicação detalhada da arquitetura

### `public/`

O diretório `public` é a porta de entrada da aplicação web.

- [public/index.php](/home/joaquim/desafios-projeto/ranking-api/public/index.php) carrega o autoload do Composer.
- Define o header `Content-Type: application/json`.
- Carrega as rotas de [routers/api.php](/home/joaquim/desafios-projeto/ranking-api/routers/api.php).
- Lê `$_SERVER['REQUEST_METHOD']` e a URI atual.
- Resolve qual controller e qual método devem ser executados.
- Executa a ação do controller.
- Serializa a resposta com `json_encode`.

Na prática, esse arquivo faz o papel de front controller.

### `routers/`

Esse diretório concentra o mapa de rotas HTTP.

- [routers/api.php](/home/joaquim/desafios-projeto/ranking-api/routers/api.php) retorna um array indexado por método HTTP.
- Cada rota aponta para uma classe de controller e um método.

Hoje existe uma rota principal:

- `GET /api/ranking` -> `RankingController::getRanking`

### `app/Http/Controllers/`

Aqui ficam os controllers, responsáveis por lidar com a camada HTTP.

- [RankingController.php](/home/joaquim/desafios-projeto/ranking-api/app/Http/Controllers/RankingController.php) lê os parâmetros `movement_id` e `movement` vindos da query string.
- Ele instancia o service de ranking.
- Chama a regra de negócio.
- Monta a resposta padrão da API com `status` e `data`.

O controller não consulta o banco diretamente. Ele apenas recebe a requisição e delega a lógica ao service.

### `app/Services/`

Os services concentram as regras de negócio.

- [RankingService.php](/home/joaquim/desafios-projeto/ranking-api/app/Services/RankingService.php) é o núcleo da funcionalidade de ranking.

Esse service executa quatro responsabilidades principais:

1. Resolve qual movimento deve ser consultado.
2. Busca os recordes relacionados àquele movimento.
3. Ordena e converte os dados em ranking posicional.
4. Retorna um payload final pronto para o controller.

Métodos importantes:

- `getRanking(?int $movementId, ?string $movementName): array`
  Recebe o filtro de movimento e devolve a estrutura final.
- `resolveMovement(...)`
  Decide se a busca do movimento será por ID ou por nome.
- `buildRanking(array $records): array`
  Percorre os recordes ordenados e calcula a posição no ranking, tratando empates quando o valor do recorde se repete.

### `app/Repositories/`

Os repositories encapsulam o acesso aos dados.

- [MovementRepository.php](/home/joaquim/desafios-projeto/ranking-api/app/Repositories/MovementRepository.php) delega buscas simples de movimento ao model.
- [PersonalRecordRepository.php](/home/joaquim/desafios-projeto/ranking-api/app/Repositories/PersonalRecordRepository.php) executa a query SQL do ranking.

O `PersonalRecordRepository` faz:

- `JOIN` entre `personal_record` e `user`;
- filtro por `movement_id`;
- ordenação decrescente por valor (`ORDER BY pr.value DESC`).

Isso deixa o service livre para trabalhar apenas na regra de posicionamento do ranking.

### `app/Models/`

Os models representam tabelas e compartilham operações genéricas de banco.

- [Model.php](/home/joaquim/desafios-projeto/ranking-api/app/Models/Model.php) é a classe base abstrata.
- [Movement.php](/home/joaquim/desafios-projeto/ranking-api/app/Models/Movement.php) representa a tabela `movement`.
- [User.php](/home/joaquim/desafios-projeto/ranking-api/app/Models/User.php) representa a tabela `user`.
- [PersonalRecord.php](/home/joaquim/desafios-projeto/ranking-api/app/Models/PersonalRecord.php) representa a tabela `personal_record`.

O model base entrega operações comuns:

- `create(array $data)`
- `findByName(string $name)`
- `find($id)`
- `has(string $field, $value)`
- `get()`
- `update($id, array $data)`
- `delete($id)`

Cada model concreto define:

- o nome real da tabela;
- os campos permitidos em `fillable`.

### `app/Database/`

Essa pasta concentra conexão, SQL de criação e seed de dados.

#### `Connection.php`

- [Connection.php](/home/joaquim/desafios-projeto/ranking-api/app/Database/Connection.php) implementa uma instância única de PDO.
- Lê o `.env` manualmente.
- Carrega [config/database.php](/home/joaquim/desafios-projeto/ranking-api/config/database.php).
- Cria a conexão com MySQL.
- Configura `PDO::ATTR_ERRMODE` como `PDO::ERRMODE_EXCEPTION`.

#### `Migrations/`

- [create_base.sql](/home/joaquim/desafios-projeto/ranking-api/app/Database/Migrations/create_base.sql) cria o banco `ranking_api`.
- [create_tables.sql](/home/joaquim/desafios-projeto/ranking-api/app/Database/Migrations/create_tables.sql) cria as tabelas:
  - `user`
  - `movement`
  - `personal_record`

Também define:

- chave primária em todas as tabelas;
- chaves estrangeiras entre `personal_record`, `user` e `movement`;
- índices para acelerar consultas por movimento, usuário e valor.

#### `Seeders/`

Os seeders inserem dados iniciais.

- [DatabaseSeeder.php](/home/joaquim/desafios-projeto/ranking-api/app/Database/Seeders/DatabaseSeeder.php) orquestra a execução dos seeders.
- [UserSeeder.php](/home/joaquim/desafios-projeto/ranking-api/app/Database/Seeders/UserSeeder.php) cria usuários base.
- [MovementSeeder.php](/home/joaquim/desafios-projeto/ranking-api/app/Database/Seeders/MovementSeeder.php) cria os movimentos.
- [PersonalRecordSeeder.php](/home/joaquim/desafios-projeto/ranking-api/app/Database/Seeders/PersonalRecordSeeder.php) popula os recordes iniciais.

Os seeders usam o método `has(...)` antes de inserir, evitando duplicação dos registros já existentes.

### `config/`

- [database.php](/home/joaquim/desafios-projeto/ranking-api/config/database.php) centraliza a leitura das credenciais e valores padrão do banco.

Ele busca os valores nas variáveis de ambiente e aplica fallback para:

- host `127.0.0.1`
- porta `3306`
- banco `ranking_api`
- usuário `root`
- senha vazia

### `script/`

Essa pasta reúne scripts utilitários para bootstrap do ambiente.

- [run_migrations.sh](/home/joaquim/desafios-projeto/ranking-api/script/run_migrations.sh) executa os SQLs de criação e depois chama o arquivo PHP de seed.
- [run_seeders.php](/home/joaquim/desafios-projeto/ranking-api/script/run_seeders.php) carrega o autoload e executa `DatabaseSeeder`.

Fluxo do `run_migrations.sh`:

1. Descobre o diretório atual do script.
2. Carrega as variáveis do `.env`.
3. Executa `create_base.sql`.
4. Executa `create_tables.sql`.
5. Chama o script PHP de seeders.

### `storage/`

Diretório reservado para arquivos gerados pela aplicação. Atualmente contém `logs/`, mas a API ainda não implementa escrita estruturada de logs nele.

### `vendor/`

Diretório gerado pelo Composer com autoload e dependências do projeto. Não é código de domínio da aplicação, mas é essencial para o carregamento automático das classes.

## Fluxo completo de uma requisição

Quando uma chamada chega em `GET /api/ranking?movement=Deadlift`, o fluxo é:

1. O servidor embutido do PHP aponta para `public/`.
2. [public/index.php](/home/joaquim/desafios-projeto/ranking-api/public/index.php) identifica a rota.
3. A rota resolve para `RankingController::getRanking`.
4. O controller coleta os parâmetros da query string.
5. O controller chama `RankingService::getRanking`.
6. O service resolve o movimento pelo repositório de movimentos.
7. O service busca os recordes no repositório de recordes.
8. O service monta as posições do ranking.
9. O controller devolve o array de resposta.
10. O `index.php` transforma o array em JSON e envia ao cliente.

## Observações

- O projeto usa PHP procedural apenas no bootstrap e scripts; a regra da aplicação está organizada em classes.
- O roteamento é manual, sem framework.
- A serialização da resposta é feita no front controller, não nos controllers.
- O banco esperado é MySQL, por causa dos scripts SQL e do DSN PDO utilizado.
