<?php

class Produto 
    {
        private $nome;
        private $local;
        private $categoria;
        private $preco;
        private $descricao;

        private function __construct($nome, $local, $categoria, $preco, $descricao);
        {
            this->name = $name;
            this->local = $local;
            this->categoria = $categoria;
            this->preco = $preco;
            this->descricao = $descricao;
        }

        function getId(){
            return $this->id;
        }
        function setId($nome){
            $this->nome = $nome;
        }

        function getNome(){
            return $this->nome;
        }
        function setNome($nome){
            $this->nome = $nome;
        }

        function getLocal(){
            return $this->local;
        }
        function setLocal($local){
            $this->local = $local;
        }

        function getCategoria(){
            return $this->categoria;
        }
        function setCategoria($categoria){
            $this->categoria = $categoria;
        }

        function getPreco(){
            return $this->preco;
        }
        function setPreco($preco){
            $this->preco = $preco;
        }

        function getDescricao(){
            return $this->descricao;
        }
        function setDescricao($descricao){
            $this->descricao = $descricao;
        }
    }

?>