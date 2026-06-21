# HEBA - Health Base

## Informação do Estudante
- **Nome:** Maria Helena Grande Abrantes Gouveia Barbosa
- **Número:** 1204961

## Instruções para Instalação e Execução da Aplicação

### 1. Colocação dos ficheiros
Coloque o projeto na diretoria `htdocs` do seu servidor local (XAMPP, MAMP, WAMP, etc) ou equivalente. A estrutura de pastas final deverá permitir o acesso via *localhost* ou *127.0.0.1*. 
*Exemplo do caminho esperado:* `htdocs/sibdas/1240961/projeto-heba/`

### 2. Configuração do URL da aplicação
Edite o ficheiro `/config/config.php` e altere a constante `BASE_URL` para o caminho em que colocou o projeto:
```php
define('BASE_URL', '/caminho/do/seu/projeto/');
```
Exemplo se o projeto estiver na diretoria `htdocs/projeto-heba/`:
```php
define('BASE_URL', '/projeto-heba/');
```
**Nota:** O ficheiro `config.php` também possui o modo de depuração ativado (`SHOW_DEBUG_BUTTONS = true`), o qual mostra botões de acesso rápido aos diferentes perfis no ecrã de login para facilitar os testes. Para ambiente de produção, modifique para `false`. Também precisará de adaptar os detalhes de conexão (host, database, username, password) de acordo com o seu servidor MySQL.

### 3. Base de Dados
Para o correto funcionamento do sistema, é necessário inicializar a base de dados. Utilize uma ferramenta como o HeidiSql e corra os seguintes ficheiros SQL (presentes na pasta `/database/`), preferencialmente por esta ordem:

1. `database.sql` - Criação de tabelas, constrangimentos e modelo relacional da base de dados.
2. `dummy_data.sql` - Inserção de dados de teste de exemplo, credenciais de acesso, locais, equipamentos e configurações do *front-office*.
3. `events.sql` - Criação dos Eventos do MySQL para o processamento de rotinas diárias (ex: notificação de stock baixo, manutenções próximas e garantias a expirar).

## Instruções para Realização dos Principais Testes da Aplicação

1. Aceda à página principal (Front-Office) através do browser (ex: `http://localhost/sibdas/1240961/projeto-heba/`).
2. Explore o design dinâmico, conteúdo da marca "HEBA" e seções públicas.
3. No final da página (roda-pé), clique em **Aceder ao Backoffice** (que reencaminha para `/login.php`).
4. Irá reparar que existem *botões de depuração de acesso rápido* no ecrã de login se o modo debug estiver ativado. Pode clicar num deles para preencher automaticamente as credenciais para diferentes níveis de permissões, ou escrever manualmente utilizando as credenciais abaixo.
5. Inicie sessão como **Administrador** para visualizar as funcionalidades totais (dashboard de métricas vitais, gestão de acessos, inventário, etc.).
6. Teste diferentes perfis utilizando a funcionalidade de "Logout" e "Login" com as restantes contas para observar a implementação de controlo de acessos nas diferentes páginas do backoffice.
7. Teste a criação/edição/remoção de dados (ex: Equipamentos, Componentes, etc.).
8. Explore a tabela do inventário, usando os filtros e pesquisa que funcionam de forma fluida.

## Credenciais de Acesso (Perfis e Utilizadores)
As passwords dos utilizadores encontram-se encriptadas na base de dados (`AES_ENCRYPT`), no entanto as credenciais em texto limpo dos perfis principais carregados via `dummy_data.sql` são as seguintes:

- **Administrador**
  - **Email:** admin@hospital.pt
  - **Password:** password01
- **Engenheiro Biomédico**
  - **Email:** eng.bio@hospital.pt
  - **Password:** password02
- **Técnico de Manutenção**
  - **Email:** tecnico@hospital.pt
  - **Password:** password03
- **Aprovisionamento**
  - **Email:** aprovisionamento@hospital.pt
  - **Password:** password04
- **Consulta / Médico**
  - **Email:** consulta@hospital.pt
  - **Password:** password05

## Informação Adicional Relevante
Deixo também alguns destaques técnicos da arquitetura da aplicação que podem ser relevantes para a avaliação do projeto:

- **Controlo de Acessos Granular (RBAC):** Existe um sistema robusto de papéis e permissões implementado que não se limita a esconder páginas no front-office (menus). O acesso a cada página e a ações cruciais de submissão/edição e eliminação é protegido e revalidado diretamente no servidor. Tentativas de acesso sem permissões redirecionam o utilizador e garantem a integridade da plataforma.
- **Automatização Inteligente no MySQL (Event Scheduler):** Em vez de validar tudo por código a cada pedido, foi criado o ficheiro de base de dados `events.sql` que ativa eventos agendados que correm em background diretamente no motor MySQL. Estes eventos monitorizam alterações diárias (como calcular quando manutenções e garantias de equipamentos expiram ou quando o stock atinge o valor crítico) e criam as respetivas notificações no painel da aplicação de forma completamente autónoma.
- **Design Responsivo e Integração UX/UI Moderna:** A interface gráfica foi construída para funcionar sem quebras tanto no telemóvel (ex: com um _sidebar_ responsivo) como no desktop. Destaca-se ainda a inclusão de bibliotecas modernas que otimizam o aspeto da plataforma, como geradores e leitores de *QR Codes* dinâmicos e o uso de `SimpleDatatables` que permite paginar, pesquisar e filtrar instantaneamente grandes listas e relatórios do inventário.
