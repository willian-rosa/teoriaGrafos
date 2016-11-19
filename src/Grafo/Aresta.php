<?php

namespace Grafo;

class Aresta {
    
    private $index;
    private $nome;
    private $peso;
    private $vertice1;
    private $vertice2;
    
    public function __construct($index, $nome, Vertice $vertice1, Vertice $vertice2, $peso) {
        $this->index    = $index;
        $this->nome     = $nome;
        $this->vertice1 = $vertice1;
        $this->vertice2 = $vertice2;
        $this->peso     = $peso;
    }
    
    /**
     * @return Vertice
     */
    public function getVertice1() {
        return $this->vertice1;
    }

    /**
     * @return Vertice
     */
    public function getVertice2() {
        return $this->vertice2;
    }
    
    /**
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }
    
    /**
     * @return integer
     */
    public function getIndex() {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getPeso() {
        return $this->peso;
    }
    
}
