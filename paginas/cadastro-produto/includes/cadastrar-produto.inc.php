<?php

class Produto 
    {
        private $nome;
        private $marca;
        private $local;
        private $categoria;
        private $preco;
        private $descricao;

        private function __construct($nome, $marca, $local, $categoria, $preco, $descricao)
        {
            $this->name = $name;
            $this->marca = $marca;
            $this->local = $local;
            $this->categoria = $categoria;
            $this->preco = $preco;
            $this->descricao = $descricao;
        }

        function getMarca(){
            return $this->marca;
        }
        function setMarca($marca){
            $this->marca = $marca;
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