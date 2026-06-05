<?php

interface Validavel
{
    public function validarDados(array $dados): array;
}

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

        $erros = $this->validarDados([
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

    public function validarDados(array $dados): array
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

        $erros = $this->validarDados([
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

    public function validarDados(array $dados): array
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
        $erros = $this->validarDados([
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

    public function validarDados(array $dados): array
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