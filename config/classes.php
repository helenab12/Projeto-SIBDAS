<?php

interface Validavel
{
    public static function validarDados(array $dados): array;
}

// Pessoas, Autenticação, Autorização, Gestão de Utilizadores e Perfis

class Pessoa implements Validavel
{
    private string $id;
    private string $nome;
    private string $email;
    private string $contactoTelefonico;
    private string $nif;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;

    public function __construct(string $id, string $nome, string $email, string $contactoTelefonico, string $nif, bool $ativo, DateTime $dataCriacao, DateTime $dataAtualizacao)
    {

        $erros = self::validarDados([
            "id" => $id,
            "nome" => $nome,
            "email" => $email,
            "contactoTelefonico" => $contactoTelefonico,
            "nif" => $nif,
            "ativo" => $ativo,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar pessoa: " . implode(", ", $erros));
        }

        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->contactoTelefonico = $contactoTelefonico;
        $this->nif = $nif;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getContactoTelefonico(): string
    {
        return $this->contactoTelefonico;
    }

    public function getNif(): string
    {
        return $this->nif;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function getDataCriacao(): DateTime
    {
        return $this->dataCriacao;
    }

    public function getDataAtualizacao(): DateTime
    {
        return $this->dataAtualizacao;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];
        if (empty($dados["id"])) {
            $erros[] = "O ID é obrigatório.";
        }

        if (empty(trim($dados["nome"]))) {
            $erros[] = "O campo Nome é obrigatório.";
        } elseif (preg_match('/\d/', $dados["nome"])) {
            $erros[] = "O campo Nome não pode conter números.";
        }

        if (empty(trim($dados["email"]))) {
            $erros[] = "O campo Email é obrigatório.";
        } elseif (!filter_var($dados["email"], FILTER_VALIDATE_EMAIL)) {
            $erros[] = "O campo Email tem de ser um email válido.";
        }

        if (empty(trim($dados["contactoTelefonico"]))) {
            $erros[] = "O campo Contacto Telefónico é obrigatório.";
        }

        if (empty(trim($dados["nif"]))) {
            $erros[] = "O campo NIF é obrigatório.";
        } elseif (preg_match('/[^0-9]/', $dados["nif"])) {
            $erros[] = "O campo NIF tem de conter apenas números.";
        } elseif (strlen($dados["nif"]) !== 9) {
            $erros[] = "O campo NIF tem de ter 9 dígitos.";
        }

        if (empty($dados["dataCriacao"])) {
            $erros[] = 'A data de criação é obrigatória.';
        } elseif (!($dados["dataCriacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataCriacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataCriacao"]) {
                $erros[] = "O campo Data de Criação tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }

        if (empty($dados["dataAtualizacao"])) {
            $erros[] = 'A data de atualização é obrigatória.';
        } elseif (!($dados["dataAtualizacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataAtualizacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataAtualizacao"]) {
                $erros[] = "O campo Data de Atualização tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }

        return $erros;
    }
}

class Utilizador implements Validavel
{
    private string $idUtilizador;
    private string $idPessoa;
    private string $password;
    private string $idPerfil;
    private string $estado;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;
    private Perfil $perfil;

    public function __construct(
        string $idUtilizador,
        string $idPessoa,
        string $password,
        string $idPerfil,
        string $estado,
        bool $ativo,
        DateTime $dataCriacao,
        DateTime $dataAtualizacao,
        Perfil $perfil
    ) {

        $erros = self::validarDados([
            "idUtilizador" => $idUtilizador,
            "idPessoa" => $idPessoa,
            "password" => $password,
            "idPerfil" => $idPerfil,
            "estado" => $estado,
            "ativo" => $ativo,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao,
            "perfil" => $perfil
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar utilizador: " . implode(", ", $erros));
        }

        $this->idUtilizador = $idUtilizador;
        $this->idPessoa = $idPessoa;
        $this->password = $password;
        $this->idPerfil = $idPerfil;
        $this->estado = $estado;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->perfil = $perfil;
    }

    public function getIdUtilizador(): string
    {
        return $this->idUtilizador;
    }

    public function getIdPessoa(): string
    {
        return $this->idPessoa;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIdPerfil(): string
    {
        return $this->idPerfil;
    }

    public function getEstado(): string
    {
        return $this->estado;
    }

    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public function getDataCriacao(): DateTime
    {
        return $this->dataCriacao;
    }

    public function getDataAtualizacao(): DateTime
    {
        return $this->dataAtualizacao;
    }

    public function getPerfil(): Perfil
    {
        return $this->perfil;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];

        if (empty(trim($dados["idUtilizador"]))) {
            $erros[] = "O ID do utilizador é obrigatório.";
        }

        if (empty(trim($dados["idPessoa"]))) {
            $erros[] = "A pessoa associada é obrigatória.";
        }

        if (empty(trim($dados["password"]))) {
            $erros[] = "A password é obrigatória.";
        } elseif (strlen($dados["password"]) < 8) {
            $erros[] = "A password deve ter pelo menos 8 caracteres.";
        }

        if (empty(trim($dados["idPerfil"]))) {
            $erros[] = "O perfil é obrigatório.";
        }

        if (empty(trim($dados["estado"]))) {
            $erros[] = "O estado é obrigatório.";
        } elseif (!in_array($dados["estado"], ['Ativo', 'Inativo'])) {
            $erros[] = "O estado deve ser 'Ativo' ou 'Inativo'.";
        }

        if (empty($dados["dataCriacao"])) {
            $erros[] = 'A data de criação é obrigatória.';
        } elseif (!($dados["dataCriacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataCriacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataCriacao"]) {
                $erros[] = "O campo Data de Criação tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }

        if (empty($dados["dataAtualizacao"])) {
            $erros[] = 'A data de atualização é obrigatória.';
        } elseif (!($dados["dataAtualizacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataAtualizacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataAtualizacao"]) {
                $erros[] = "O campo Data de Atualização tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }

        if (empty($dados["perfil"])) {
            $erros[] = 'O perfil é obrigatório.';
        } elseif (!($dados["perfil"] instanceof Perfil)) {
            $erros[] = "O perfil tem de ser uma instância da classe Perfil.";
        }

        return $erros;
    }
}

class Perfil implements Validavel
{
    private string $idPerfil;
    private string $nome;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;

    public function __construct(string $idPerfil, string $nome, DateTime $dataCriacao, DateTime $dataAtualizacao)
    {
        $erros = self::validarDados([
            "idPerfil" => $idPerfil,
            "nome" => $nome,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar perfil: " . implode(", ", $erros));
        }

        $this->idPerfil = $idPerfil;
        $this->nome = $nome;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
    }

    public function getIdPerfil(): string
    {
        return $this->idPerfil;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getDataCriacao(): DateTime
    {
        return $this->dataCriacao;
    }

    public function getDataAtualizacao(): DateTime
    {
        return $this->dataAtualizacao;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];

        if (empty(trim($dados["idPerfil"]))) {
            $erros[] = "O ID do perfil é obrigatório.";
        }

        if (empty(trim($dados["nome"]))) {
            $erros[] = "O nome do perfil é obrigatório.";
        }

        if (empty($dados["dataCriacao"])) {
            $erros[] = 'A data de criação é obrigatória.';
        } elseif (!($dados["dataCriacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataCriacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataCriacao"]) {
                $erros[] = "O campo Data de Criação tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }

        if (empty($dados["dataAtualizacao"])) {
            $erros[] = 'A data de atualização é obrigatória.';
        } elseif (!($dados["dataAtualizacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataAtualizacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataAtualizacao"]) {
                $erros[] = "O campo Data de Atualização tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }

        return $erros;
    }
}

// Conteúdo do Site

class ConteudoTexto implements Validavel
{
    private int $idConteudo;
    private string $chaveSecao;
    private string $valor;

    public function __construct(int $idConteudo, string $chaveSecao, string $valor)
    {
        $erros = self::validarDados([
            'idConteudo' => $idConteudo,
            'chaveSecao' => $chaveSecao,
            'valor' => $valor,
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar conteúdo de texto: " . implode(", ", $erros));
        }

        $this->idConteudo = $idConteudo;
        $this->chaveSecao = $chaveSecao;
        $this->valor = $valor;
    }

    public function getIdConteudo(): int
    {
        return $this->idConteudo;
    }
    public function getChaveSecao(): string
    {
        return $this->chaveSecao;
    }
    public function getValor(): string
    {
        return $this->valor;
    }

    // Permite usar o objeto diretamente como string: echo $texto;
    public function __toString(): string
    {
        return $this->valor;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];

        if (empty($dados['idConteudo'])) {
            $erros[] = "O ID do conteúdo é obrigatório.";
        }

        if (empty(trim($dados['chaveSecao'] ?? ''))) {
            $erros[] = "A chave de secção é obrigatória.";
        }

        if (!isset($dados['valor'])) {
            $erros[] = "O valor do conteúdo é obrigatório.";
        }

        return $erros;
    }
}

class CartaoFuncionalidade implements Validavel
{
    private int $idCartao;
    private string $titulo;
    private string $descricao;
    private string $icone;
    private int $ordem;
    private bool $ativo;

    public function __construct(int $idCartao, string $titulo, string $descricao, string $icone, int $ordem, bool $ativo = true)
    {
        $erros = self::validarDados([
            'idCartao' => $idCartao,
            'titulo' => $titulo,
            'descricao' => $descricao,
            'icone' => $icone,
            'ordem' => $ordem,
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar cartão de funcionalidade: " . implode(", ", $erros));
        }

        $this->idCartao = $idCartao;
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->icone = $icone;
        $this->ordem = $ordem;
        $this->ativo = $ativo;
    }

    public function getIdCartao(): int
    {
        return $this->idCartao;
    }
    public function getTitulo(): string
    {
        return $this->titulo;
    }
    public function getDescricao(): string
    {
        return $this->descricao;
    }
    public function getIcone(): string
    {
        return $this->icone;
    }
    public function getOrdem(): int
    {
        return $this->ordem;
    }
    public function getAtivo(): bool
    {
        return $this->ativo;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];

        if (empty($dados['idCartao'])) {
            $erros[] = "O ID do cartão é obrigatório.";
        }

        if (empty(trim($dados['titulo'] ?? ''))) {
            $erros[] = "O título do cartão é obrigatório.";
        }

        if (empty(trim($dados['descricao'] ?? ''))) {
            $erros[] = "A descrição do cartão é obrigatória.";
        }

        if (!isset($dados['icone']) || trim($dados['icone']) === '') {
            $erros[] = "O ícone do cartão é obrigatório.";
        }

        if (!isset($dados['ordem']) || $dados['ordem'] < 0) {
            $erros[] = "A ordem do cartão é obrigatória e deve ser positiva.";
        }

        return $erros;
    }
}

class ConteudoPagina implements ArrayAccess
{
    private array $textos = [];

    private array $cartoes = [];

    // Textos
    public function adicionarTexto(ConteudoTexto $texto): void
    {
        $this->textos[$texto->getChaveSecao()] = $texto;
    }

    public function getTexto(string $chave): ?ConteudoTexto
    {
        return $this->textos[$chave] ?? null;
    }

    public function getTextos(): array
    {
        return $this->textos;
    }

    // Cartões
    public function adicionarCartao(CartaoFuncionalidade $cartao): void
    {
        $this->cartoes[] = $cartao;
    }

    public function getCartoes(): array
    {
        return $this->cartoes;
    }

    // ArrayAccess — acesso por chave [$chave]
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->textos[$offset]);
    }

    public function offsetGet(mixed $offset): ?string
    {
        return isset($this->textos[$offset])
            ? htmlspecialchars($this->textos[$offset]->getValor())
            : null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new BadMethodCallException('ConteudoPagina é apenas de leitura. Usar adicionarTexto() ou adicionarCartao().');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new BadMethodCallException('ConteudoPagina é apenas de leitura.');
    }

    // Função estática para carregar todos os dados da base de dados
    public static function carregarDaBaseDeDados(PDO $ligacao): self
    {
        $pagina = new self();

        // 1. Carregar textos (ConteudoFrontOffice)
        $stmt = execute_query(
            "SELECT idConteudo, chaveSecao, valor
             FROM ConteudoFrontOffice
             ORDER BY idConteudo ASC",
            [],
            $ligacao
        );
        $textos = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($textos as $row) {
            $pagina->adicionarTexto(new ConteudoTexto(
                (int) $row->idConteudo,
                $row->chaveSecao,
                $row->valor
            ));
        }

        // 2. Carregar cartões (CartaoFuncionalidade)
        $stmt = execute_query(
            "SELECT idCartao, titulo, descricao, icone, ordem, ativo
             FROM CartaoFuncionalidade
             WHERE ativo = 1
             ORDER BY ordem ASC",
            [],
            $ligacao
        );
        $cartoes = $stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($cartoes as $row) {
            $pagina->adicionarCartao(new CartaoFuncionalidade(
                (int) $row->idCartao,
                $row->titulo,
                $row->descricao,
                $row->icone,
                (int) $row->ordem,
                (bool) $row->ativo
            ));
        }

        return $pagina;
    }
}

// Inbox

class InboxState
{
    private string $name;
    private string $class;

    public function __construct(string $name, string $class)
    {
        $this->name = $name;
        $this->class = $class;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }
}

class InboxRequest implements Validavel
{
    private int $id;
    private InboxState $state;
    private string $date;
    private string $name;
    private string $institution;
    private string $email;
    private string $message;

    public function __construct(int $id, InboxState $state, string $date, string $name, string $institution, string $email, string $message)
    {
        $erros = self::validarDados([
            'id' => $id,
            'date' => $date,
            'name' => $name,
            'institution' => $institution,
            'email' => $email,
            'message' => $message,
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar pedido de demonstração: " . implode(", ", $erros));
        }

        $this->id = $id;
        $this->state = $state;
        $this->date = $date;
        $this->name = $name;
        $this->institution = $institution;
        $this->email = $email;
        $this->message = $message;
    }

    public function getId(): int
    {
        return $this->id;
    }
    public function getState(): InboxState
    {
        return $this->state;
    }
    public function getDate(): string
    {
        return $this->date;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getInstitution(): string
    {
        return $this->institution;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getMessage(): string
    {
        return $this->message;
    }

    public function __get(string $name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return null;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];

        if (empty($dados['id'])) {
            $erros[] = "O ID do pedido é obrigatório.";
        }

        if (empty(trim($dados['name'] ?? ''))) {
            $erros[] = "O nome é obrigatório.";
        } elseif (preg_match('/\d/', $dados['name'])) {
            $erros[] = "O nome não pode conter números.";
        }

        if (empty(trim($dados['email'] ?? ''))) {
            $erros[] = "O email é obrigatório.";
        } elseif (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
            $erros[] = "O email deve ser um endereço de email válido.";
        }

        if (empty(trim($dados['institution'] ?? ''))) {
            $erros[] = "A organização é obrigatória.";
        }

        if (mb_strlen($dados['message']) > 400) {
            $erros[] = "A mensagem não pode exceder os 400 caracteres.";
        }

        return $erros;
    }
}