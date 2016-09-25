<?php
namespace Grafo;

class Grafo{

    public static $inifito = 100000;
    private static $prefixNomeAresta = 'e';


    private $verteces = array();
    
    /**
     *
     * @var $arestas \Grafo\Aresta[] 
     */
    private $arestas = array();
    private $digrafo;
    
    private $listaAdjacencia;
    private $matrizAdjacencia;
    private $matrizDistancia;


    public function __construct($strVerteces, $strArestas, $digrafo) {
        $this->verteces = $this->converteStringEmVerteces($strVerteces);
        $this->arestas  = $this->converteStringEmArestas($strArestas);
                
        $this->digrafo = $digrafo;
        
    }
    
    /**
     * @param type $string
     * @return type
     */
    private function removeChaveString($string){
        return preg_replace('/^({)|(})$/', '', $string);
    }

    /**
     * @param type $strVerteces
     * @return \Grafo\Vertece[]
     * @throws Exception
     */
    private function converteStringEmVerteces($strVerteces){
    
        $string = $this->removeChaveString($strVerteces);
        
        $explode = \preg_split('/,/', $string);
        
        if(!is_array($explode)){
            throw new \Exception('Erro ao converter string para verteces');
        }
        
        $verteces = array();
        $indexVertece = 0;
        
        foreach ($explode as $nomeVertece){
            $verteces[] = new Vertece($indexVertece++, $nomeVertece);
        }
        
        return $verteces;
    }
    
    private function converteStringEmArestas($strArestas){
        
        $string = $this->removeChaveString($strArestas);
        
        $explode = \preg_split('/[\(|\)]/', $string, -1, PREG_SPLIT_NO_EMPTY);
        
        if(!is_array($explode)){
            throw new \Exception('Erro ao converter string para arestas');
        }
        
        $arestas = array();
        
        $indexAresta = 0;
        
        foreach ($explode as $chaveAresta){
            
            $nomesArestas = \preg_split('/,/', $chaveAresta);
            
            $vertece1   = $this->buscaVertecePorNome($nomesArestas[0]);
            $vertece2   = $this->buscaVertecePorNome($nomesArestas[1]);
            $peso       = $nomesArestas[2];

            $index = $indexAresta++;
            
            $nomeAresta = static::$prefixNomeAresta.($indexAresta);
            
            $aresta = new Aresta($index, $nomeAresta, $vertece1, $vertece2, $peso);
            
            $arestas[] = $aresta;
            
        }
        
        return $arestas;
        
    }
    
    private function buscaVertecePorNome($nome){
        
        $verteces = $this->verteces;
        reset($verteces);
        
        while ($vertece = current($verteces)) {

            if($vertece->getNome() === $nome){
                return $vertece;
            }
            
            next($verteces);
            
        }
        
        return null;
        
    }
    
    public function hasDigrafo(){
        return $this->digrafo;
    }

    public function gerarListaDeAdjacencia(){
        
        if($this->listaAdjacencia){
            return $this->listaAdjacencia;
        }
        
        $adjacencia = array();
        
        foreach ($this->verteces as $vertece){
            foreach ($this->arestas as $aresta){
                
                if($aresta->getVertece1() === $vertece){
                    $adjacencia[$vertece->getIndex()][] = $aresta->getVertece2();
                }
                
                
                if(!$this->digrafo && $aresta->getVertece2() === $vertece){
                    $adjacencia[$vertece->getIndex()][] = $aresta->getVertece1();
                }
                
            }
        }
        
        $this->listaAdjacencia = $adjacencia;
        
        return $adjacencia;
        
    }
    
    public function gerarMatrizDeAdjacencia(){
        
        if($this->matrizAdjacencia){
            return $this->matrizAdjacencia;
        }
        
        $totalVerteces = count($this->verteces);
        
        $vetor  = array_fill(0, $totalVerteces, 0);
        $matriz = array_fill(0, $totalVerteces, $vetor);
        
        foreach ($this->arestas as $aresta){
            
            $matriz[$aresta->getVertece1()->getIndex()][$aresta->getVertece2()->getIndex()] = $aresta->getPeso();
            
            if(!$this->digrafo){
                $matriz[$aresta->getVertece2()->getIndex()][$aresta->getVertece1()->getIndex()] = $aresta->getPeso();
            }
            
        }
        
        $this->matrizAdjacencia = $matriz;

        return $matriz;
        
    }
    
    public function gerarMatrizDeIncidencia(){
        
        $totalVerteces  = count($this->verteces);
        $totalArestas   = count($this->arestas);
        
        $vetor  = array_fill(0, $totalArestas, 0);
        $matriz = array_fill(0, $totalVerteces, $vetor);
        
        foreach ($this->arestas as $aresta){

            if($this->digrafo){
                $matriz[$aresta->getVertece1()->getIndex()][$aresta->getIndex()]--;
            }  else {
                $matriz[$aresta->getVertece1()->getIndex()][$aresta->getIndex()]++;
            }
            
            $matriz[$aresta->getVertece2()->getIndex()][$aresta->getIndex()]++;
            
        }
        
        return $matriz;
        
        
    }
    
    public function gerarListaAresta(){
        
        $inicio     = array();
        $termino    = array();
        
        foreach ($this->arestas as $aresta){
            
            $inicio[]   = $aresta->getVertece1();
            $termino[]      = $aresta->getVertece2();
            
        }
        
        $listaAresta = array($inicio, $termino);

        return $listaAresta;
        
    }
    
    /**
     * @param type $index
     * @return Vertece
     */
    public function buscaVertecePorIndex($index){
        
        $verteces = $this->verteces;
        reset($verteces);
        
        while ($vertece = current($verteces)) {

            if($vertece->getIndex() === $index){
                return $vertece;
            }
            
            next($verteces);
            
        }
        
        return null;
        
    }
    
    /**
     * 
     * @param type $index
     * @return Aresta
     */
    public function buscaArestaPorIndex($index){
        
        $arestas = $this->arestas;
        reset($arestas);
        
        while ($aresta = current($arestas)) {

            if($aresta->getIndex() === $index){
                return $aresta;
            }
            
            next($arestas);
            
        }
        
        return null;
        
    }
    
    public function gerarMatrizDistancia(){
        
        if($this->matrizDistancia){
            return $this->matrizDistancia;
        }
        
        $matrizDistancia = array();
        
        foreach ($this->verteces as $vertece){
            
            $algoritmoDijkstra = new AlgoritmoDijkstra($vertece,
                                                        $this->verteces,
                                                        $this->gerarListaDeAdjacencia(),
                                                        $this->gerarMatrizDeAdjacencia());
            
            $matrizDistancia[$vertece->getIndex()] = $algoritmoDijkstra->gerlarLinhaDistancias();
            
        }
        
        $this->matrizDistancia = $matrizDistancia;
        
        return $matrizDistancia;
        
    }
    
    public function gerarExcentricidade(array $matrisDistancia){
        
        $tamanhoMatriz  = count($matrisDistancia);
        
        //inicia excentricidade
        $excentricidade = array();
        $excentricidadeValores = array_fill(0, $tamanhoMatriz, 0);
        $excentricidade['saida']    = $excentricidadeValores;
        $excentricidade['retorno']  = $excentricidadeValores;
                        
        for ($i = 0; $i < $tamanhoMatriz; $i++) {
            for ($j = 0; $j < $tamanhoMatriz; $j++) {
                
                $distancia = $matrisDistancia[$i][$j];
                
                if($distancia != static::$inifito){
                    
                    if($excentricidade['retorno'][$j] < $distancia){
                        $excentricidade['retorno'][$j] = $distancia; 
                    }

                    if($excentricidade['saida'][$i] < $distancia){
                        $excentricidade['saida'][$i] = $distancia; 
                    }
                    
                }
                
            }
        }
                
        return $excentricidade;
        
    }
    
    public function buscarRaioSaida(){
        
        $excentricidade = $this->gerarExcentricidade($this->gerarMatrizDistancia());
        
        return $this->buscarRaio($excentricidade['saida']);
        
    }
    
    public function buscarRaioRetorno(){
        
        $excentricidade = $this->gerarExcentricidade($this->gerarMatrizDistancia());
        
        return $this->buscarRaio($excentricidade['retorno']);
        
    }
    
    public function buscarRaio($excentricidade){
        
        $raio = static::$inifito;
        
        foreach ($excentricidade as $valor){
            if($valor < $raio){
                $raio = $valor;
            }
        }
        
        return $raio;
    }
    
    /**
     * 
     * @param array $excentricidade
     * @return array
     */
    public function buscarCentro($excentricidade, $raio){
        
        $centro = array();
        
        for($i = 0; $i < count($excentricidade); $i++){
            if($excentricidade[$i] === $raio){
                $centro[] = $this->verteces[$i];
            }
        }
        
        return $centro;
    }
    
    public function buscarCentroSaida(){

        $excentricidade = $this->gerarExcentricidade($this->gerarMatrizDistancia());
        
        $raio = $this->buscarRaio($excentricidade['saida']);
        
        return $this->buscarCentro($excentricidade['saida'], $raio);
        
    }
    
    public function buscarCentroRetorno(){

        $excentricidade = $this->gerarExcentricidade($this->gerarMatrizDistancia());
        
        $raio = $this->buscarRaio($excentricidade['retorno']);
        
        return $this->buscarCentro($excentricidade['retorno'], $raio);
        
    }
    
    public function somaMatrizComMatrizTransposta(array $matriz){
        
        $matrizSoma = array();
        $tamanhoMatrix = count($matriz);
            
        for ($i = 0; $i < $tamanhoMatrix; $i++) {
            for ($j = 0; $j < $tamanhoMatrix; $j++) {
                $matrizSoma[$i][$j] = $matriz[$i][$j]+$matriz[$j][$i];
            }
        }
        
        return $matrizSoma;
        
    }

    public function buscarRaioSaidaRetoro(){
 
        $matriz = $this->somaMatrizComMatrizTransposta($this->gerarMatrizDistancia());
        
        $excentricidade = $this->gerarExcentricidade($matriz);
        
        return $this->buscarRaio($excentricidade['saida']);
        
    }
    
    public function buscarCentroSaidaRetorno(){

        $matriz = $this->somaMatrizComMatrizTransposta($this->gerarMatrizDistancia());
        
        $excentricidade = $this->gerarExcentricidade($matriz);
        
        $raio   = $this->buscarRaio($excentricidade['saida']);
        
        return $this->buscarCentro($excentricidade['saida'], $raio);
        
    }
    
    
}