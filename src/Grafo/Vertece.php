<?php

namespace Grafo;

class Vertece {
    
    private $nome;
    private $index;
    
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


    
    
}
