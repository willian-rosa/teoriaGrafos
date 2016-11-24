<?php
namespace Grafo;

class Grafo{

    public static $inifito = 100000;
    private static $prefixNomeAresta = 'e';


    private $vertices = array();
    
    /**
     *
     * @var $arestas \Grafo\Aresta[] 
     */
    private $arestas = array();
    private $digrafo;
    
    private $listaAdjacencia;
    private $matrizAdjacencia;
    private $matrizDistancia;


    public function __construct($strVertices, $strArestas, $digrafo) {
        $this->vertices = $this->converteStringEmVertices($strVertices);
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
     * @param type $strVertices
     * @return \Grafo\Vertice[]
     * @throws Exception
     */
    private function converteStringEmVertices($strVertices){
    
        $string = $this->removeChaveString($strVertices);
        
        $explode = \preg_split('/,/', $string);
        
        if(!is_array($explode)){
            throw new \Exception('Erro ao converter string para vertices');
        }
        
        $vertices = array();
        $indexVertice = 0;
        
        foreach ($explode as $nomeVertice){
            $vertices[] = new Vertice($indexVertice++, $nomeVertice);
        }
        
        return $vertices;
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
            
            $vertice1   = $this->buscaVerticePorNome($nomesArestas[0]);
            $vertice2   = $this->buscaVerticePorNome($nomesArestas[1]);
            $peso       = $nomesArestas[2];

            $index = $indexAresta++;
            
            $nomeAresta = static::$prefixNomeAresta.($indexAresta);
            
            $aresta = new Aresta($index, $nomeAresta, $vertice1, $vertice2, $peso);
            
            $arestas[] = $aresta;
            
        }
        
        return $arestas;
        
    }
    
    private function buscaVerticePorNome($nome){
        
        $vertices = $this->vertices;
        reset($vertices);
        
        while ($vertice = current($vertices)) {

            if($vertice->getNome() === $nome){
                return $vertice;
            }
            
            next($vertices);
            
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
        
        foreach ($this->vertices as $vertice){
            foreach ($this->arestas as $aresta){
                
                if($aresta->getVertice1() === $vertice){
                    $adjacencia[$vertice->getIndex()][] = $aresta->getVertice2();
                    $aresta->getVertice1()->addAdjacente($aresta->getVertice2());
                }
                
                
                if(!$this->digrafo && $aresta->getVertice2() === $vertice){
                    $adjacencia[$vertice->getIndex()][] = $aresta->getVertice1();
                    $aresta->getVertice2()->addAdjacente($aresta->getVertice1());
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
        
        $totalVertices = count($this->vertices);
        
        $vetor  = array_fill(0, $totalVertices, 0);
        $matriz = array_fill(0, $totalVertices, $vetor);
        
        foreach ($this->arestas as $aresta){
            
            $matriz[$aresta->getVertice1()->getIndex()][$aresta->getVertice2()->getIndex()] = $aresta->getPeso();
            
            if(!$this->digrafo){
                $matriz[$aresta->getVertice2()->getIndex()][$aresta->getVertice1()->getIndex()] = $aresta->getPeso();
            }
            
        }
        
        $this->matrizAdjacencia = $matriz;

        return $matriz;
        
    }
    
    public function gerarMatrizDeIncidencia(){
        
        $totalVertices  = count($this->vertices);
        $totalArestas   = count($this->arestas);
        
        $vetor  = array_fill(0, $totalArestas, 0);
        $matriz = array_fill(0, $totalVertices, $vetor);
        
        foreach ($this->arestas as $aresta){

            if($this->digrafo){
                $matriz[$aresta->getVertice1()->getIndex()][$aresta->getIndex()]--;
            }  else {
                $matriz[$aresta->getVertice1()->getIndex()][$aresta->getIndex()]++;
            }
            
            $matriz[$aresta->getVertice2()->getIndex()][$aresta->getIndex()]++;
            
        }
        
        return $matriz;
        
        
    }
    
    public function gerarListaAresta(){
        
        $inicio     = array();
        $termino    = array();
        
        foreach ($this->arestas as $aresta){
            
            $inicio[]   = $aresta->getVertice1();
            $termino[]      = $aresta->getVertice2();
            
        }
        
        $listaAresta = array($inicio, $termino);

        return $listaAresta;
        
    }
    
    /**
     * @param type $index
     * @return Vertice
     */
    public function buscaVerticePorIndex($index){
        
        $vertices = $this->vertices;
        reset($vertices);
        
        while ($vertice = current($vertices)) {

            if($vertice->getIndex() === $index){
                return $vertice;
            }
            
            next($vertices);
            
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
        
        foreach ($this->vertices as $vertice){
            
            $algoritmoDijkstra = new AlgoritmoDijkstra($vertice,
                                                        $this->vertices,
                                                        $this->gerarListaDeAdjacencia(),
                                                        $this->gerarMatrizDeAdjacencia());
            
            $matrizDistancia[$vertice->getIndex()] = $algoritmoDijkstra->gerlarLinhaDistancias();
            
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
                $centro[] = $this->vertices[$i];
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
 
    public function gerarArvoreDeCobertura(){
        $kruskal = new AlgoritmoKruskal($this->vertices, $this->arestas);
        
        return $kruskal->gerarArvoreCobertura();
    }
    
    /**
     * 
     * @return Cor[]
     */
    public function gerarAlgoritmoColorir(){
        
        $algoritmoColorir = new AlgoritmoColorir($this->gerarListaDeAdjacencia(), $this->vertices);
        
        return $algoritmoColorir->buscar();
    }
    
    
    
}