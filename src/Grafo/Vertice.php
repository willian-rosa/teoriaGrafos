<?php

namespace Grafo;

class Vertice {
    
    private $nome;
    private $index;
    private $cor;
    private $adjacentes = array();


    public function __construct($index, $nome) {
        $this->nome     = $nome;
        $this->index    = $index;
    }
    
    public function getIndex() {
        return $this->index;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setIndex($index) {
        $this->index = $index;
        return $this;
    }

    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    public function __toString() {
        return $this->nome;
    }
    
    public function setCor(Cor $cor) {
        $this->cor = $cor;
        return $this;
    }
    
    public function clearCor() {
        $this->cor = null;
        return $this;
    }

    /**
     * @return Cor
     */
    public function getCor() {
        return $this->cor;
    }
    
    public function getAdjacentes() {
        return $this->adjacentes;
    }

    public function setAdjacentes($adjacentes) {
        $this->adjacentes = $adjacentes;
        return $this;
    }
    
    public function addAdjacente(Vertice $adjacente) {
        $this->adjacentes[] = $adjacente;
    }


    
}
