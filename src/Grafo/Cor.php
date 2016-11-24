<?php

namespace Grafo;

class Cor{
    
    private $nome;
    private $vertices = array();


    public function __construct($nome) {
        $this->nome = $nome;
    }
    
    public function getNome() {
        return $this->nome;
    }

    /**
     * 
     * @return Vertice
     */
    public function getVertices() {
        return $this->vertices;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function addVertice(Vertice $vertice) {
        $this->vertices[] = $vertice;
    }
}