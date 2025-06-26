<?php
    class Usuario
    {
        private $id;
        private $nome;
        private $telefone;
        private $email;
        private $senha;
        private $confirmarSenha;

        function receberDadosDoFormulario($conexao) {

            //AVISO: cuidado ao receber dados de um formulário e enviá-los ao banco de dados. Se o seu código não contiver os comandos apropriados, o servidor estará sujeito ao tipo de invasão conhecido como INJEÇÃO DE SQL
            $this->nome = trim($conexao->escape_string($_POST["nome-usuario"]));
            $this->telefone = trim($conexao->escape_string($_POST["telefone"]));
            $this->email = trim($conexao->escape_string($_POST["email"]));
            $this->senha = trim($conexao->escape_string($_POST["senha"]));
            $this->confirmarSenha = trim($conexao->escape_string($_POST["confirmacao-senha"]));

            //verificando se o usuário preencheu todos os campos do formulário
            if(empty($this->nome) || empty($this->telefone) || empty($this->email) || empty($this->senha) || empty($this->confirmarSenha)) {
                die("Por favor, preencha todos os campos do formulário.");
            }
            //verificando se o usuário digitou a senha corretamente
            if($this->senha != $this->confirmarSenha) {
                die("As senhas digitadas não conferem. Por favor, tente novamente.");
            }
        }
    
        function cadastrar($conexao, $nomeDaTabela5)
        {
            // Coloquei o id como NULL, pois ele é auto-incremento no banco de dados
            $sql = "INSERT $nomeDaTabela5 VALUES(
                    NULL,
                    '$this->nome',  
                    '$this->telefone',
                    '$this->email',
                    '$this->senha',
                    '$this->confirmarSenha'
                    )";
            $conexao->query($sql) or die($conexao->error);
        }
    }

    

?>