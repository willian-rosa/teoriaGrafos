angular.module('TeoriaGrafos', ['isteven-multi-select']).controller('produtos', function ($scope, $http){
    
    function factoryNovoProduto(){
        return {nome:'', adjacentes: []};
    }
    
    function converteStringVertices(produtos){
        
        if(produtos.length === 0){
            return false;
        }
        
        var nomesProdutos = produtos.map(function (produto){
            return produto.nome;
        });
        
        return '{'+nomesProdutos.join(',')+'}';
    }
    
    function converteStringArestas(produtos){
        
        if(produtos.length <= 1){
            return false;
        }
        
        var nomesArestas = [];
        
        for (var index in produtos){
            
            var produto = produtos[index];
            
            produto.adjacentes.forEach(function (adjacente){
                var aresta = '('+produto.nome+','+adjacente.nome+',1)';
                nomesArestas.push(aresta);
            });
            
        }
        
        if(nomesArestas.length){
            return '{'+nomesArestas.join('')+'}';
        }

        return false;
        
    }
    
    
    
   $scope.novoProduto = factoryNovoProduto();
   $scope.produtos = [];
   
   $scope.prateleiras = [];
   
   $scope.cadastrarProduto = function (produto){
       
        produto.nome = produto.nome.trim();
       
        if(produto.nome === ''){
            return ;
        }
       
       
        $scope.produtos.push(produto);
        $scope.novoProduto = factoryNovoProduto();
       
       
        var vertices = converteStringVertices($scope.produtos),
            arestas = converteStringArestas($scope.produtos);
    
        if(vertices){
            
            var data = {vertices: vertices};
            
            if(arestas){
                data.arestas = arestas;
            }
            
            $http.post('api.php', data).success(function (data){
                $scope.prateleiras= data;
            });
            
        }
    
       
   };
   
   
});