<?php

namespace Grafo;

class Aresta {
    
    private $index;
    private $nome;
    private $vertece1;
    private $vertece2;
    
    public function __construct($index, $nome, Vertece $vertece1, Vertece $vertece2) {
        $this->index    = $index;
        $this->nome     = $nome;
        $this->vertece1 = $vertece1;
        $this->vertece2 = $vertece2;
    }
    
    /**
     * @return Vertece
     */
    public function getVertece1() {
        return $this->vertece1;
    }

    /**
     * @return Vertece
     */
    public function getVertece2() {
        return $this->vertece2;
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


    
}