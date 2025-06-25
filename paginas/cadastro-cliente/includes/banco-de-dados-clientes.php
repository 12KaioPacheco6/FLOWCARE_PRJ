<?php

class Produto 
    {
        private $nome;
        private $telefone;
        private $email;
        private $senha;
        private $confirmarSenha;

        private function __construct($nome, $telefone, $email, $senha, $confirmarSenha)
        {
            $this->nome = $nome;
            $this->telefone = $telefone;
            $this->email = $email;
            $this->senha = $senha;
            $this->confirmarSenha = $confirmarSenha;
        }

        function getNome(){
            return $this->nome;
        }
        function setNome($nome){
            $this->nome = $nome;
        }

        function getTelefone(){
            return $this->telefone;
        }
        function Telefone($telefone){
            $this->telefone = $telefone;
        }

        function getEmail(){
            return $this->email;
        }
        function setEmail($email){
            $this->email = $email;
        }

        function getSenha(){
            return $this->senha;
        }
        function setSenha($senha){
            $this->senha = $senha;
        }

       function confirmarSenha($confirmacao){
        return $this->senha === $confirmacao;
    }

    }
?>