<?php

interface Validavel
{
    public static function validarDados(array $dados): array;
}

// Pessoas, Autenticação, Autorização, Gestão de Utilizadores e Perfis

enum Funcao: string
{
    case ADMINISTRADOR = 'Administrador';
    case ENGENHEIRO = 'Engenheiro';
    case MEDICO = 'Médico';
    case ASSISTENTE = 'Assistente';
    case ENFERMEIRO = 'Enfermeiro';
    case TECNICO = 'Técnico';
    case FORNECEDOR = 'Fornecedor';
    case DIRETOR = 'Diretor';
    case OUTRO = 'Outro';
}

class Pessoa implements Validavel
{
    private string $id;
    private string $nome;
    private string $email;
    private string $contactoTelefonico;
    private string $nif;
    private Funcao $funcao;
    private string $departamento;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;

    public function __construct(string $id, string $nome, string $email, string $contactoTelefonico, string $nif, Funcao $funcao, string $departamento, bool $ativo, DateTime $dataCriacao, DateTime $dataAtualizacao)
    {
        $erros = self::validarDados([
            "id" => $id,
            "nome" => $nome,
            "email" => $email,
            "contactoTelefonico" => $contactoTelefonico,
            "nif" => $nif,
            "funcao" => $funcao,
            "departamento" => $departamento,
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
        $this->funcao = $funcao;
        $this->departamento = $departamento;
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

    public function getFuncao(): Funcao
    {
        return $this->funcao;
    }

    public function getDepartamento(): string
    {
        return $this->departamento;
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
            $erros[] = "O formato do e-mail é inválido.";
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

        $funcao = $dados["funcao"] ?? null;
        if (empty($funcao) || (is_string($funcao) && empty(trim($funcao)))) {
            $erros[] = "A função é obrigatória.";
        }

        if (empty(trim($dados["departamento"] ?? ''))) {
            $erros[] = "O departamento é obrigatório.";
        }

        return $erros;
    }
}

class Utilizador implements Validavel
{
    private string $idUtilizador;
    private string $idPessoa;
    private string $emailAutenticacao;
    private string $password;
    private string $idPerfil;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;
    private Perfil $perfil;

    public function __construct(
        string $idUtilizador,
        string $idPessoa,
        string $emailAutenticacao,
        string $password,
        string $idPerfil,
        bool $ativo,
        DateTime $dataCriacao,
        DateTime $dataAtualizacao,
        Perfil $perfil
    ) {

        $erros = self::validarDados([
            "idUtilizador" => $idUtilizador,
            "idPessoa" => $idPessoa,
            "emailAutenticacao" => $emailAutenticacao,
            "password" => $password,
            "idPerfil" => $idPerfil,
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
        $this->emailAutenticacao = $emailAutenticacao;
        $this->password = $password;
        $this->idPerfil = $idPerfil;
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

    public function getEmailAutenticacao(): string
    {
        return $this->emailAutenticacao;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getIdPerfil(): string
    {
        return $this->idPerfil;
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

        if (empty(trim($dados["emailAutenticacao"]))) {
            $erros[] = "O email de autenticação é obrigatório.";
        } elseif (!filter_var($dados["emailAutenticacao"], FILTER_VALIDATE_EMAIL)) {
            $erros[] = "O email de autenticação tem de ser um email válido.";
        }

        if (empty(trim($dados["password"]))) {
            $erros[] = "A password é obrigatória.";
        } elseif (strlen($dados["password"]) < 8) {
            $erros[] = "A password deve ter pelo menos 8 caracteres.";
        }

        if (empty(trim($dados["idPerfil"]))) {
            $erros[] = "O perfil é obrigatório.";
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
    private array $permissoes;

    public function __construct(string $idPerfil, string $nome, DateTime $dataCriacao, DateTime $dataAtualizacao, array $permissoes = [])
    {
        $erros = self::validarDados([
            "idPerfil" => $idPerfil,
            "nome" => $nome,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao,
            "permissoes" => $permissoes
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar perfil: " . implode(", ", $erros));
        }

        $this->idPerfil = $idPerfil;
        $this->nome = $nome;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->permissoes = $permissoes;
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

    public function getPermissoes(): array
    {
        return $this->permissoes;
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

        if (isset($dados["permissoes"])) {
            if (!is_array($dados["permissoes"])) {
                $erros[] = "O campo permissões tem de ser um array.";
            } else {
                foreach ($dados["permissoes"] as $perm) {
                    if (!($perm instanceof Permissao)) {
                        $erros[] = "Todas as permissões têm de ser instâncias da classe Permissao.";
                        break;
                    }
                }
            }
        }

        return $erros;
    }
}

class Permissao implements Validavel
{
    private int $idPermissao;
    private string $chave;
    private string $descricao;

    public function __construct(int $idPermissao, string $chave, string $descricao)
    {
        $erros = self::validarDados([
            "idPermissao" => $idPermissao,
            "chave" => $chave,
            "descricao" => $descricao
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar permissão: " . implode(", ", $erros));
        }

        $this->idPermissao = $idPermissao;
        $this->chave = $chave;
        $this->descricao = $descricao;
    }

    public function getIdPermissao(): int
    {
        return $this->idPermissao;
    }

    public function getChave(): string
    {
        return $this->chave;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];

        if (!isset($dados["idPermissao"]) || !is_int($dados["idPermissao"])) {
            $erros[] = "O ID da permissão tem de ser um número inteiro válido.";
        }

        if (empty(trim($dados["chave"]))) {
            $erros[] = "A chave da permissão é obrigatória.";
        }

        if (empty(trim($dados["descricao"]))) {
            $erros[] = "A descrição da permissão é obrigatória.";
        }

        return $erros;
    }
}

// Fornecedores

enum TipoFornecedor: string
{
    case FABRICANTE = 'Fabricante';
    case DISTRIBUIDOR = 'Distribuidor';
    case ASSISTENCIA_TECNICA = 'Assistência Técnica';
    case CONSUMIVEIS = 'Consumíveis';
}

class Fornecedor implements Validavel
{
    private string $idFornecedor;
    private string $nome;
    private string $nifFornecedor;
    private string $contactoTelefonico;
    private string $email;
    private string $website;
    private ?string $idPessoaResponsavel;
    private TipoFornecedor $tipoFornecedor;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;

    private ?Pessoa $pessoaResponsavel;

    public function __construct(
        string $idFornecedor,
        string $nome,
        string $nifFornecedor,
        string $contactoTelefonico,
        string $email,
        string $website,
        ?string $idPessoaResponsavel,
        TipoFornecedor $tipoFornecedor,
        bool $ativo,
        DateTime $dataCriacao,
        DateTime $dataAtualizacao,
        ?Pessoa $pessoaResponsavel = null
    ) {
        $erros = self::validarDados([
            "idFornecedor" => $idFornecedor,
            "nome" => $nome,
            "nifFornecedor" => $nifFornecedor,
            "contactoTelefonico" => $contactoTelefonico,
            "email" => $email,
            "website" => $website,
            "idPessoaResponsavel" => $idPessoaResponsavel,
            "tipoFornecedor" => $tipoFornecedor,
            "ativo" => $ativo,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar fornecedor: " . implode(", ", $erros));
        }

        $this->idFornecedor = $idFornecedor;
        $this->nome = $nome;
        $this->nifFornecedor = $nifFornecedor;
        $this->contactoTelefonico = $contactoTelefonico;
        $this->email = $email;
        $this->website = $website;
        $this->idPessoaResponsavel = $idPessoaResponsavel;
        $this->tipoFornecedor = $tipoFornecedor;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->pessoaResponsavel = $pessoaResponsavel;
    }

    public function getIdFornecedor(): string
    {
        return $this->idFornecedor;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getNifFornecedor(): string
    {
        return $this->nifFornecedor;
    }

    public function getContactoTelefonico(): string
    {
        return $this->contactoTelefonico;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getWebsite(): string
    {
        return $this->website;
    }

    public function getIdPessoaResponsavel(): ?string
    {
        return $this->idPessoaResponsavel;
    }

    public function getTipoFornecedor(): TipoFornecedor
    {
        return $this->tipoFornecedor;
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

    public function getPessoaResponsavel(): ?Pessoa
    {
        return $this->pessoaResponsavel;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];

        if (empty(trim($dados["idFornecedor"]))) {
            $erros[] = "O ID é obrigatório.";
        }

        if (empty(trim($dados["nome"]))) {
            $erros[] = "O campo Nome é obrigatório.";
        }

        if (empty(trim($dados["nifFornecedor"]))) {
            $erros[] = "O campo NIF é obrigatório.";
        } elseif (preg_match('/[^0-9]/', $dados["nifFornecedor"])) {
            $erros[] = "O campo NIF tem de conter apenas números.";
        } elseif (strlen($dados["nifFornecedor"]) !== 9) {
            $erros[] = "O campo NIF tem de ter 9 dígitos.";
        }

        if (empty(trim($dados["email"]))) {
            $erros[] = "O campo Email é obrigatório.";
        } elseif (!filter_var($dados["email"], FILTER_VALIDATE_EMAIL)) {
            $erros[] = "O formato do e-mail é inválido.";
        }

        if (empty(trim($dados["contactoTelefonico"]))) {
            $erros[] = "O campo Contacto Telefónico é obrigatório.";
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

        $tipo = $dados["tipoFornecedor"] ?? null;
        if (empty($tipo)) {
            $erros[] = "O tipo de fornecedor é obrigatório.";
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
    private string $descricao;

    public function __construct(int $idConteudo, string $chaveSecao, string $valor, string $descricao = '')
    {
        $erros = self::validarDados([
            'idConteudo' => $idConteudo,
            'chaveSecao' => $chaveSecao,
            'valor' => $valor,
            'descricao' => $descricao,
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar conteúdo de texto: " . implode(", ", $erros));
        }

        $this->idConteudo = $idConteudo;
        $this->chaveSecao = $chaveSecao;
        $this->valor = $valor;
        $this->descricao = $descricao;
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
    public function getDescricao(): string
    {
        return $this->descricao;
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

    public static array $icon_map = [
        'document' => [
            'label' => '📄 Documento',
            'svg' => '<path d="M6 22a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h8a2.4 2.4 0 0 1 1.704.706l3.588 3.588A2.4 2.4 0 0 1 20 8v12a2 2 0 0 1-2 2z" /><path d="M14 2v5a1 1 0 0 0 1 1h5" /><path d="M10 9H8" /><path d="M16 13H8" /><path d="M16 17H8" />'
        ],
        'wrench' => [
            'label' => '🔧 Chave Inglesa',
            'svg' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.106-3.105c.32-.322.863-.22.983.218a6 6 0 0 1-8.259 7.057l-7.91 7.91a1 1 0 0 1-2.999-3l7.91-7.91a6 6 0 0 1 7.057-8.259c.438.12.54.662.219.984z" />'
        ],
        'package' => [
            'label' => '📦 Caixa / Pacote',
            'svg' => '<path d="M11 21.73a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73z" /><path d="M12 22V12" /><polyline points="3.29 7 12 12 20.71 7" /><path d="m7.5 4.27 9 5.15" />'
        ],
        'sparkles' => [
            'label' => '✨ Estrelas (IA)',
            'svg' => '<path d="M11.017 2.814a1 1 0 0 1 1.966 0l1.051 5.558a2 2 0 0 0 1.594 1.594l5.558 1.051a1 1 0 0 1 0 1.966l-5.558 1.051a2 2 0 0 0-1.594 1.594l-1.051 5.558a1 1 0 0 1-1.966 0l-1.051-5.558a2 2 0 0 0-1.594-1.594l-5.558-1.051a1 1 0 0 1 0-1.966l5.558-1.051a2 2 0 0 0 1.594-1.594z" /><path d="M20 2v4" /><path d="M22 4h-4" /><circle cx="4" cy="20" r="2" />'
        ],
        'users' => [
            'label' => '👥 Utilizadores',
            'svg' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><path d="M16 3.128a4 4 0 0 1 0 7.744" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><circle cx="9" cy="7" r="4" />'
        ],
        'grid' => [
            'label' => '📊 Bento Grelha',
            'svg' => '<rect width="7" height="9" x="3" y="3" rx="1" /><rect width="7" height="5" x="14" y="3" rx="1" /><rect width="7" height="9" x="14" y="12" rx="1" /><rect width="7" height="5" x="3" y="16" rx="1" />'
        ]
    ];

    public static function resolverIcone(string $icon_type, string $custom_icon): string
    {
        if ($icon_type === 'other') {
            return $custom_icon;
        }
        if (!isset(self::$icon_map[$icon_type])) {
            throw new Exception("Ícone predefinido selecionado é inválido.");
        }
        return self::$icon_map[$icon_type]['svg'];
    }

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
            "SELECT idConteudo, chaveSecao, valor, descricao
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
                $row->valor,
                $row->descricao ?? ''
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

class EstadoPedidoDemonstracao
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

class PedidoDemonstracao implements Validavel
{
    private int $id;
    private EstadoPedidoDemonstracao $state;
    private string $date;
    private string $name;
    private string $institution;
    private string $email;
    private string $message;

    public function __construct(int $id, EstadoPedidoDemonstracao $state, string $date, string $name, string $institution, string $email, string $message)
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
    public function getState(): EstadoPedidoDemonstracao
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

// Hierarquia de Localizações

class Edificio implements Validavel
{
    private int $idEdificio;
    private string $nome;
    private array $pisos = [];

    public function __construct(int $idEdificio, string $nome)
    {
        $erros = self::validarDados([
            'idEdificio' => (string) $idEdificio,
            'nome' => $nome
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar edifício: " . implode(", ", $erros));
        }

        $this->idEdificio = $idEdificio;
        $this->nome = $nome;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];
        if (empty(trim($dados['nome'] ?? ''))) {
            $erros[] = "O nome do edifício é obrigatório.";
        }
        return $erros;
    }

    public function getIdEdificio(): int
    {
        return $this->idEdificio;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function addPiso(Piso $piso): void
    {
        $this->pisos[] = $piso;
    }

    public function getPisos(): array
    {
        return $this->pisos;
    }
}

class Piso implements Validavel
{
    private int $idPiso;
    private int $idEdificio;
    private string $nome;
    private array $servicos = [];

    public function __construct(int $idPiso, int $idEdificio, string $nome)
    {
        $erros = self::validarDados([
            'idPiso' => (string) $idPiso,
            'idEdificio' => (string) $idEdificio,
            'nome' => $nome
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar piso: " . implode(", ", $erros));
        }

        $this->idPiso = $idPiso;
        $this->idEdificio = $idEdificio;
        $this->nome = $nome;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];
        if (empty(trim($dados['idEdificio'] ?? ''))) {
            $erros[] = "O ID do edifício é obrigatório.";
        }
        if (empty(trim($dados['nome'] ?? ''))) {
            $erros[] = "O nome do piso é obrigatório.";
        }
        return $erros;
    }

    public function getIdPiso(): int
    {
        return $this->idPiso;
    }

    public function getIdEdificio(): int
    {
        return $this->idEdificio;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function addServico(Servico $servico): void
    {
        $this->servicos[] = $servico;
    }

    public function getServicos(): array
    {
        return $this->servicos;
    }
}

class Servico implements Validavel
{
    private int $idServico;
    private int $idPiso;
    private string $nome;
    private array $salas = [];

    public function __construct(int $idServico, int $idPiso, string $nome)
    {
        $erros = self::validarDados([
            'idServico' => (string) $idServico,
            'idPiso' => (string) $idPiso,
            'nome' => $nome
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar serviço: " . implode(", ", $erros));
        }

        $this->idServico = $idServico;
        $this->idPiso = $idPiso;
        $this->nome = $nome;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];
        if (empty(trim($dados['idPiso'] ?? ''))) {
            $erros[] = "O ID do piso é obrigatório.";
        }
        if (empty(trim($dados['nome'] ?? ''))) {
            $erros[] = "O nome do serviço é obrigatório.";
        }
        return $erros;
    }

    public function getIdServico(): int
    {
        return $this->idServico;
    }

    public function getIdPiso(): int
    {
        return $this->idPiso;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function addSala(Localizacao $sala): void
    {
        $this->salas[] = $sala;
    }

    public function getSalas(): array
    {
        return $this->salas;
    }
}

class Localizacao implements Validavel
{
    private int $idLocalizacao;
    private int $idServico;
    private string $nomeSala;

    public function __construct(int $idLocalizacao, int $idServico, string $nomeSala)
    {
        $erros = self::validarDados([
            'idLocalizacao' => (string) $idLocalizacao,
            'idServico' => (string) $idServico,
            'nomeSala' => $nomeSala
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar localização: " . implode(", ", $erros));
        }

        $this->idLocalizacao = $idLocalizacao;
        $this->idServico = $idServico;
        $this->nomeSala = $nomeSala;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];
        if (empty(trim($dados['idServico'] ?? ''))) {
            $erros[] = "O ID do serviço é obrigatório.";
        }
        if (empty(trim($dados['nomeSala'] ?? ''))) {
            $erros[] = "O nome da sala é obrigatório.";
        }
        return $erros;
    }

    public function getIdLocalizacao(): int
    {
        return $this->idLocalizacao;
    }

    public function getIdServico(): int
    {
        return $this->idServico;
    }

    public function getNomeSala(): string
    {
        return $this->nomeSala;
    }
}

// Categorias

class Categoria implements Validavel
{
    private string $idCategoria;
    private string $nome;
    private string $codigo;
    private string $descricao;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;

    public function __construct(string $idCategoria, string $nome, string $codigo, string $descricao, bool $ativo, DateTime $dataCriacao, DateTime $dataAtualizacao)
    {
        $erros = self::validarDados([
            "idCategoria" => $idCategoria,
            "nome" => $nome,
            "codigo" => $codigo,
            "descricao" => $descricao,
            "ativo" => $ativo,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar categoria: " . implode(", ", $erros));
        }

        $this->idCategoria = $idCategoria;
        $this->nome = $nome;
        $this->codigo = $codigo;
        $this->descricao = $descricao;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
    }

    public function getIdCategoria(): string
    {
        return $this->idCategoria;
    }

    public function getNome(): string
    {
        return $this->nome;
    }

    public function getCodigo(): string
    {
        return $this->codigo;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
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

    public function getEquipamentosCount(): int
    {
        try {
            $ligacao = connect_to_db();

            $stmt = execute_query(
                "SELECT COUNT(*) as count FROM Equipamento WHERE idCategoria = :idCategoria AND ativo = 1",
                ['idCategoria' => $this->idCategoria],
                $ligacao
            );

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['count'];
        } catch (Exception $e) {
            return 0;
        }
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];
        if (empty(trim($dados["nome"] ?? ''))) {
            $erros[] = "O campo Nome da Categoria é obrigatório.";
        }
        if (empty(trim($dados["codigo"] ?? '')) || strlen(trim($dados['codigo'])) > 5) {
            $erros[] = "O campo Código é obrigatório.";
        }
        if (empty(trim($dados["descricao"] ?? ''))) {
            $erros[] = "O campo Descrição é obrigatório.";
        }
        if (empty($dados["ativo"] ?? '')) {
            $erros[] = "O campo Ativo é obrigatório.";
        }
        if (empty($dados["dataCriacao"] ?? '')) {
            $erros[] = "O campo Data de Criação é obrigatório.";
        } elseif (!($dados["dataCriacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataCriacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataCriacao"]) {
                $erros[] = "O campo Data de Criação tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }
        if (empty($dados["dataAtualizacao"] ?? '')) {
            $erros[] = "O campo Data de Atualização é obrigatório.";
        } elseif (!($dados["dataAtualizacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataAtualizacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataAtualizacao"]) {
                $erros[] = "O campo Data de Atualização tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }
        return $erros;
    }
}

// Equipamentos

enum EstadoEquipamento: string
{
    case ATIVO = 'Ativo';
    case EM_MANUTENCAO = 'Em manutenção';
    case INATIVO = 'Inativo';
    case EM_CALIBRACAO = 'Em calibração';
    case EM_QUARENTENA = 'Em Quarentena';
    case ABATIDO = 'Abatido';
}

enum CriticidadeEquipamento: string
{
    case BAIXA = 'Baixa';
    case MEDIA = 'Média';
    case ALTA = 'Alta';
    case CRITICO = 'Crítico';
}

enum TipoEntrada: string
{
    case COMPRA = 'Compra';
    case DOACAO = 'Doação';
    case ALUGUER = 'Aluguer';
    case EMPRESTIMO = 'Empréstimo';
}

class Marca implements Validavel
{
    private string $idMarca;
    private string $nome;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;

    public function __construct(string $idMarca, string $nome, bool $ativo, DateTime $dataCriacao, DateTime $dataAtualizacao)
    {
        $erros = self::validarDados([
            "idMarca" => $idMarca,
            "nome" => $nome,
            "ativo" => $ativo,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar marca: " . implode(", ", $erros));
        }

        $this->idMarca = $idMarca;
        $this->nome = $nome;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
    }

    public function getIdMarca(): string
    {
        return $this->idMarca;
    }

    public function getNome(): string
    {
        return $this->nome;
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
        if (empty(trim($dados["nome"] ?? ''))) {
            $erros[] = "O campo Nome é obrigatório.";
        }
        return $erros;
    }
}

class Equipamento implements Validavel
{
    private string $idEquipamento;
    private ?string $idCategoria;
    private string $codigoInterno;
    private string $designacao;
    private ?string $idMarca;
    private string $modelo;
    private string $numeroSerie;
    private ?DateTime $dataAquisicao;
    private ?DateTime $dataFabrico;
    private float $custoAquisicao;
    private TipoEntrada $tipoEntrada;
    private EstadoEquipamento $estadoAtual;
    private CriticidadeEquipamento $criticidade;
    private string $observacoes;
    private ?string $idLocalizacao;
    private bool $arquivado;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;
    private ?string $marcaNome;

    public function __construct(
        string $idEquipamento,
        ?string $idCategoria,
        string $codigoInterno,
        string $designacao,
        ?string $idMarca,
        string $modelo,
        string $numeroSerie,
        ?DateTime $dataAquisicao,
        ?DateTime $dataFabrico,
        float $custoAquisicao,
        TipoEntrada $tipoEntrada,
        EstadoEquipamento $estadoAtual,
        CriticidadeEquipamento $criticidade,
        string $observacoes,
        ?string $idLocalizacao,
        bool $arquivado,
        bool $ativo,
        DateTime $dataCriacao,
        DateTime $dataAtualizacao,
        ?string $marcaNome = null
    ) {
        $erros = self::validarDados([
            "idEquipamento" => $idEquipamento,
            "codigoInterno" => $codigoInterno,
            "designacao" => $designacao,
            "tipoEntrada" => $tipoEntrada,
            "estadoAtual" => $estadoAtual,
            "criticidade" => $criticidade,
            "ativo" => $ativo,
            "arquivado" => $arquivado,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar equipamento: " . implode(", ", $erros));
        }

        $this->idEquipamento = $idEquipamento;
        $this->idCategoria = $idCategoria;
        $this->codigoInterno = $codigoInterno;
        $this->designacao = $designacao;
        $this->idMarca = $idMarca;
        $this->modelo = $modelo;
        $this->numeroSerie = $numeroSerie;
        $this->dataAquisicao = $dataAquisicao;
        $this->dataFabrico = $dataFabrico;
        $this->custoAquisicao = $custoAquisicao;
        $this->tipoEntrada = $tipoEntrada;
        $this->estadoAtual = $estadoAtual;
        $this->criticidade = $criticidade;
        $this->observacoes = $observacoes;
        $this->idLocalizacao = $idLocalizacao;
        $this->arquivado = $arquivado;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->marcaNome = $marcaNome;
    }

    public function getIdEquipamento(): string
    {
        return $this->idEquipamento;
    }
    public function getIdCategoria(): ?string
    {
        return $this->idCategoria;
    }
    public function getCodigoInterno(): string
    {
        return $this->codigoInterno;
    }
    public function getDesignacao(): string
    {
        return $this->designacao;
    }
    public function getIdMarca(): ?string
    {
        return $this->idMarca;
    }
    public function getModelo(): string
    {
        return $this->modelo;
    }
    public function getNumeroSerie(): string
    {
        return $this->numeroSerie;
    }
    public function getDataAquisicao(): ?DateTime
    {
        return $this->dataAquisicao;
    }
    public function getDataFabrico(): ?DateTime
    {
        return $this->dataFabrico;
    }
    public function getCustoAquisicao(): float
    {
        return $this->custoAquisicao;
    }
    public function getTipoEntrada(): TipoEntrada
    {
        return $this->tipoEntrada;
    }
    public function getEstadoAtual(): EstadoEquipamento
    {
        return $this->estadoAtual;
    }
    public function getCriticidade(): CriticidadeEquipamento
    {
        return $this->criticidade;
    }
    public function getObservacoes(): string
    {
        return $this->observacoes;
    }
    public function getIdLocalizacao(): ?string
    {
        return $this->idLocalizacao;
    }
    public function getArquivado(): bool
    {
        return $this->arquivado;
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
    public function getMarcaNome(): ?string
    {
        return $this->marcaNome;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];
        if (empty(trim($dados["codigoInterno"] ?? ''))) {
            $erros[] = "O campo Código Interno é obrigatório.";
        } elseif (strlen(trim($dados["codigoInterno"])) > 20) {
            $erros[] = "O campo Código Interno não pode ter mais de 20 caracteres.";
        }
        if (empty(trim($dados["designacao"] ?? ''))) {
            $erros[] = "O campo Designação é obrigatório.";
        }
        if (isset($dados["modelo"]) && strlen(trim($dados["modelo"])) > 255) {
            $erros[] = "O campo Modelo não pode ter mais de 255 caracteres.";
        }
        if (isset($dados["numeroSerie"]) && strlen(trim($dados["numeroSerie"])) > 255) {
            $erros[] = "O campo Número de Série não pode ter mais de 255 caracteres.";
        }
        if (isset($dados["custoAquisicao"]) && is_numeric($dados["custoAquisicao"]) && $dados["custoAquisicao"] < 0) {
            $erros[] = "O Custo de Aquisição não pode ser negativo.";
        }
        $dataFabrico = null;
        if (!empty($dados["dataFabrico"])) {
            if ($dados["dataFabrico"] instanceof DateTime) {
                $dataFabrico = clone $dados["dataFabrico"];
            } else {
                $d = DateTime::createFromFormat('Y-m-d', $dados["dataFabrico"]);
                if (!$d || $d->format('Y-m-d') !== $dados["dataFabrico"]) {
                    $erros[] = "O campo Data de Fabrico tem de ser uma data válida no formato AAAA-MM-DD.";
                } else {
                    $dataFabrico = clone $d;
                }
            }
            if ($dataFabrico && (int) $dataFabrico->format('Y') < 1950) {
                $erros[] = "A Data de Fabrico não pode ser antes de 1950.";
            }
            if ($dataFabrico && $dataFabrico > new DateTime('today')) {
                $erros[] = "A Data de Fabrico não pode ser no futuro.";
            }
        }
        $dataAquisicao = null;
        if (!empty($dados["dataAquisicao"])) {
            if ($dados["dataAquisicao"] instanceof DateTime) {
                $dataAquisicao = clone $dados["dataAquisicao"];
            } else {
                $d = DateTime::createFromFormat('Y-m-d', $dados["dataAquisicao"]);
                if (!$d || $d->format('Y-m-d') !== $dados["dataAquisicao"]) {
                    $erros[] = "O campo Data de Aquisição tem de ser uma data válida no formato AAAA-MM-DD.";
                } else {
                    $dataAquisicao = clone $d;
                }
            }
            if ($dataAquisicao && (int) $dataAquisicao->format('Y') < 1950) {
                $erros[] = "A Data de Aquisição não pode ser antes de 1950.";
            }
            if ($dataAquisicao && $dataAquisicao > new DateTime('today')) {
                $erros[] = "A Data de Aquisição não pode ser no futuro.";
            }
        }

        if ($dataFabrico && $dataAquisicao && $dataFabrico > $dataAquisicao) {
            $erros[] = "A Data de Fabrico não pode ser posterior à Data de Aquisição.";
        }
        if (!isset($dados["tipoEntrada"])) {
            $erros[] = "O campo Tipo Entrada é obrigatório.";
        }
        if (!isset($dados["estadoAtual"])) {
            $erros[] = "O campo Estado Atual é obrigatório.";
        }
        if (!isset($dados["criticidade"])) {
            $erros[] = "O campo Criticidade é obrigatório.";
        }
        return $erros;
    }
}

// Documentos

enum TipoDocumento: string
{
    case MANUAL_UTILIZADOR = 'Manual de Utilizador';
    case MANUAL_SERVICO = 'Manual de Serviço';
    case CERTIFICADO_CALIBRACAO = 'Certificado de Calibração';
    case CONTRATO_MANUTENCAO = 'Contrato de Manutenção';
    case FATURA_GUIA = 'Fatura/Guia';
    case DECLARACAO_CONFORMIDADE = 'Declaração de Conformidade';
    case RELATORIO_TECNICO = 'Relatório Técnico';
    case GARANTIA = 'Garantia';
}

class Documento implements Validavel
{
    private string $idDocumento;
    private TipoDocumento $tipo;
    private string $nome;
    private ?string $caminhoFicheiro;
    private ?DateTime $dataDocumento;
    private ?DateTime $dataValidade;
    private ?string $idEquipamento;
    private ?string $idFornecedor;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;
    private ?string $fornecedorNome;

    public function __construct(
        string $idDocumento = "",
        TipoDocumento $tipo = TipoDocumento::MANUAL_UTILIZADOR,
        string $nome = "",
        ?string $caminhoFicheiro = null,
        ?DateTime $dataDocumento = null,
        ?DateTime $dataValidade = null,
        ?string $idEquipamento = null,
        ?string $idFornecedor = null,
        bool $ativo = true,
        ?DateTime $dataCriacao = null,
        ?DateTime $dataAtualizacao = null,
        ?string $fornecedorNome = null
    ) {
        $erros = self::validarDados([
            'nome' => $nome,
            'tipo' => $tipo->value,
            'caminhoFicheiro' => $caminhoFicheiro,
            'dataDocumento' => $dataDocumento ? $dataDocumento->format('Y-m-d') : null,
            'dataValidade' => $dataValidade ? $dataValidade->format('Y-m-d') : null,
        ]);
        
        if (!empty($erros)) {
            throw new Exception("Erro ao criar documento: " . implode(", ", $erros));
        }

        $this->idDocumento = $idDocumento;
        $this->tipo = $tipo;
        $this->nome = $nome;
        $this->caminhoFicheiro = $caminhoFicheiro;
        $this->dataDocumento = $dataDocumento;
        $this->dataValidade = $dataValidade;
        $this->idEquipamento = $idEquipamento;
        $this->idFornecedor = $idFornecedor;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao ?? new DateTime();
        $this->dataAtualizacao = $dataAtualizacao ?? new DateTime();
        $this->fornecedorNome = $fornecedorNome;
    }

    public function getIdDocumento(): string
    {
        return $this->idDocumento;
    }
    public function getTipo(): TipoDocumento
    {
        return $this->tipo;
    }
    public function getNome(): string
    {
        return $this->nome;
    }
    public function getCaminhoFicheiro(): ?string
    {
        return $this->caminhoFicheiro;
    }
    public function getDataDocumento(): ?DateTime
    {
        return $this->dataDocumento;
    }
    public function getDataValidade(): ?DateTime
    {
        return $this->dataValidade;
    }
    public function getIdEquipamento(): ?string
    {
        return $this->idEquipamento;
    }
    public function getIdFornecedor(): ?string
    {
        return $this->idFornecedor;
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
    public function getFornecedorNome(): ?string
    {
        return $this->fornecedorNome;
    }

    public static function validarDados(array $dados): array
    {
        $erros = [];
        
        if (empty(trim($dados['nome'] ?? ''))) {
            $erros[] = "O campo Nome é obrigatório.";
        } elseif (strlen(trim($dados['nome'])) > 255) {
            $erros[] = "O campo Nome não pode ter mais de 255 caracteres.";
        }

        if (empty(trim($dados['tipo'] ?? ''))) {
            $erros[] = "O campo Tipo de Documento é obrigatório.";
        } else {
            $tipoValido = false;
            foreach (TipoDocumento::cases() as $t) {
                if ($t->value === $dados['tipo']) {
                    $tipoValido = true;
                    break;
                }
            }
            if (!$tipoValido) {
                $erros[] = "Tipo de Documento inválido.";
            }
        }

        if (isset($dados['caminhoFicheiro']) && strlen(trim($dados['caminhoFicheiro'])) > 255) {
            $erros[] = "O caminho do ficheiro não pode ter mais de 255 caracteres.";
        }

        $dataDocObj = null;
        if (!empty($dados['dataDocumento'])) {
            if ($dados['dataDocumento'] instanceof DateTime) {
                $dataDocObj = clone $dados['dataDocumento'];
            } else {
                $d = DateTime::createFromFormat('Y-m-d', $dados['dataDocumento']);
                if (!$d || $d->format('Y-m-d') !== $dados['dataDocumento']) {
                    $erros[] = "A Data do Documento tem de ser uma data válida no formato AAAA-MM-DD.";
                } else {
                    $dataDocObj = clone $d;
                }
            }
        }

        $dataValObj = null;
        if (!empty($dados['dataValidade'])) {
            if ($dados['dataValidade'] instanceof DateTime) {
                $dataValObj = clone $dados['dataValidade'];
            } else {
                $d = DateTime::createFromFormat('Y-m-d', $dados['dataValidade']);
                if (!$d || $d->format('Y-m-d') !== $dados['dataValidade']) {
                    $erros[] = "A Data de Validade tem de ser uma data válida no formato AAAA-MM-DD.";
                } else {
                    $dataValObj = clone $d;
                }
            }
        }

        if ($dataDocObj && $dataValObj && $dataValObj < $dataDocObj) {
            $erros[] = "A Data de Validade não pode ser anterior à Data do Documento.";
        }

        return $erros;
    }
}

// Componentes

class Componente implements Validavel
{
    private string $idComponente;
    private string $codigoInterno;
    private string $descricao;
    private int $stock;
    private int $stockMinimo;
    private float $preco;
    private string $idLocalizacao;
    private bool $ativo;
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;
    private ?string $idCategoria;

    public function __construct(string $idComponente, string $codigoInterno, string $descricao, int $stock, int $stockMinimo, float $preco, string $idLocalizacao, bool $ativo, DateTime $dataCriacao, DateTime $dataAtualizacao, ?string $idCategoria = null)
    {
        $erros = self::validarDados([
            "idComponente" => $idComponente,
            "codigoInterno" => $codigoInterno,
            "descricao" => $descricao,
            "stock" => $stock,
            "stockMinimo" => $stockMinimo,
            "preco" => $preco,
            "idLocalizacao" => $idLocalizacao,
            "ativo" => $ativo,
            "dataCriacao" => $dataCriacao,
            "dataAtualizacao" => $dataAtualizacao
        ]);

        if (!empty($erros)) {
            throw new Exception("Erro ao criar componente: " . implode(", ", $erros));
        }

        $this->idComponente = $idComponente;
        $this->codigoInterno = $codigoInterno;
        $this->descricao = $descricao;
        $this->stock = $stock;
        $this->stockMinimo = $stockMinimo;
        $this->preco = $preco;
        $this->idLocalizacao = $idLocalizacao;
        $this->ativo = $ativo;
        $this->dataCriacao = $dataCriacao;
        $this->dataAtualizacao = $dataAtualizacao;
        $this->idCategoria = $idCategoria;
    }

    public function getIdComponente(): string
    {
        return $this->idComponente;
    }

    public function getCodigoInterno(): string
    {
        return $this->codigoInterno;
    }

    public function getDescricao(): string
    {
        return $this->descricao;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getStockMinimo(): int
    {
        return $this->stockMinimo;
    }

    public function getPreco(): float
    {
        return $this->preco;
    }

    public function getIdCategoria(): ?string
    {
        return $this->idCategoria;
    }

    public function getIdLocalizacao(): string
    {
        return $this->idLocalizacao;
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
        if (empty(trim($dados["codigoInterno"] ?? '')) || strlen(trim($dados['codigoInterno'])) > 20) {
            $erros[] = "O campo Código Interno é obrigatório e não pode ter mais de 20 caracteres.";
        }
        if (empty(trim($dados["descricao"] ?? ''))) {
            $erros[] = "O campo Descrição é obrigatório.";
        }
        if (!isset($dados["stock"]) || !is_numeric($dados["stock"]) || (int) $dados["stock"] < 0) {
            $erros[] = "O campo Stock tem de ser um número inteiro maior ou igual a zero.";
        }
        if (!isset($dados["stockMinimo"]) || !is_numeric($dados["stockMinimo"]) || (int) $dados["stockMinimo"] < 0) {
            $erros[] = "O campo Stock Mínimo tem de ser um número inteiro maior ou igual a zero.";
        }
        if (!isset($dados["preco"]) || !is_numeric($dados["preco"]) || (float) $dados["preco"] < 0) {
            $erros[] = "O campo Preço tem de ser um número maior ou igual a zero.";
        }
        if (empty(trim($dados["idLocalizacao"] ?? ''))) {
            $erros[] = "O campo Localização é obrigatório.";
        }
        if (!isset($dados["ativo"])) {
            $erros[] = "O campo Ativo é obrigatório.";
        }
        if (empty($dados["dataCriacao"] ?? '')) {
            $erros[] = "O campo Data de Criação é obrigatório.";
        } elseif (!($dados["dataCriacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataCriacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataCriacao"]) {
                $erros[] = "O campo Data de Criação tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }
        if (empty($dados["dataAtualizacao"] ?? '')) {
            $erros[] = "O campo Data de Atualização é obrigatório.";
        } elseif (!($dados["dataAtualizacao"] instanceof DateTime)) {
            $d = DateTime::createFromFormat('Y-m-d', $dados["dataAtualizacao"]);
            if (!$d || $d->format('Y-m-d') !== $dados["dataAtualizacao"]) {
                $erros[] = "O campo Data de Atualização tem de ser uma data válida no formato AAAA-MM-DD.";
            }
        }
        return $erros;
    }
}
