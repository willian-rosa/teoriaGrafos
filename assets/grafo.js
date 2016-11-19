var contentInterface = document.getElementById('content-interface');
var renderer, camera, scene, font, prepareNewVertex, prepareNewEdge = null;

var vertexs = [], groupsVertexs = [];
var groupVertexSelected = null;

var groupsEdges = [];
var groupEdgeSelected = null;

var animation = false;

var mouse = new THREE.Vector2();
var raycaster = new THREE.Raycaster()
var intersection = new THREE.Vector3();
var plane = new THREE.Plane();
var canvasWidth = 800, canvasHeight = 600;


var loader = new THREE.FontLoader();
loader.load( 'fonts/helvetiker_regular.typeface.json', function ( fontLoad ) {
    
    font = fontLoad;

    init();
    animate();

} );

function factoryVertex(text, positionX, positionY){
    
    var groupVertex = new THREE.Group();
    groupVertex.position.x = positionX;
    groupVertex.position.y = positionY;
    groupVertex.name = text;

    var material    = new THREE.MeshBasicMaterial( { color: 0x11558D } );
    var vertex      = new THREE.Mesh(new THREE.CircleGeometry( 1, 15 ), material);
    groupVertex.add(vertex);
    

    var textGeo  = new THREE.TextGeometry(text, {font: font, size: 0.7, height: 0, curveSegments: 3 });
    var textMesh = new THREE.Mesh( textGeo, new THREE.MeshBasicMaterial({color: 0x000000}));
    
    textMesh.position.x = -0.8;
    textMesh.position.y = -0.3;
    
    groupVertex.add(textMesh);
  
    scene.add(groupVertex);
   
    vertexs.push(vertex);
    groupsVertexs.push(groupVertex);
    
}

function factoryEdge(text, positionX, positionY){

    var groupEdge = new THREE.Group();
    groupEdge.std = text;

    var material = new THREE.LineBasicMaterial({color: 0x000000, linewidth: 2 });

    var geometry = new THREE.Geometry();
    
    geometry.vertices.push(new THREE.Vector3(positionX, positionY, 0 ), new THREE.Vector3(positionX, positionY, 0 ) );
    
    var edgeCalda = new THREE.Line( geometry, material );
    edgeCalda.name = 'calda';

    groupEdge.add(edgeCalda);
  
    scene.add(groupEdge);
   
    groupsEdges.push(groupEdge);

    return groupEdge;
    
}

function removeEdge(groupEdgeSelecionado){
    if(groupEdgeSelecionado){
        var i = groupsEdges.indexOf(groupEdgeSelecionado);
        if (i > -1) {
            groupsEdges.splice(i, 1);
            scene.remove(groupEdgeSelecionado);
        }
    }

}

function newVertex(){
    var input = document.getElementById('interface-vertice');
    prepareNewVertex = {type: 'vertex', 'value': input.value};
    input.value = '';
}

function newEdge(){
    var input = document.getElementById('interface-aresta');
    prepareNewEdge = {type: 'edge', 'value': input.value};
    input.value = '';
}

function updateRaycaster(event){

    event.preventDefault();

    var positonScrollY = renderer.domElement.offsetTop-document.documentElement.scrollTop;
    var positonScrollX = renderer.domElement.offsetLeft-document.documentElement.scrollLeft;

    mouse.x = ((event.clientX-positonScrollX) / canvasWidth ) * 2 - 1;
    mouse.y = - ((event.clientY-positonScrollY) / canvasHeight ) * 2 + 1;

    raycaster.setFromCamera( mouse, camera );

}

function getPositionMouse(event){

    updateRaycaster(event);

    plane.setFromNormalAndCoplanarPoint( camera.getWorldDirection( plane.normal ), new THREE.Vector3() );

    raycaster.ray.intersectPlane( plane, intersection );

    intersection.sub(new THREE.Vector3());

    return intersection;

}

function getGroupVertexInteesect(event){
    
    var selectedGroupVertex = null;

    updateRaycaster(event);

    var verticesEncontrados = raycaster.intersectObjects(vertexs);

    if(verticesEncontrados.length > 0){
        for(var i in groupsVertexs){
            if(verticesEncontrados[0].object.uuid === groupsVertexs[i].children[0].uuid){
                selectedGroupVertex = groupsVertexs[i];
            }
        }
    }

    return selectedGroupVertex;


}

function onMouseDown(event){
    
    event.preventDefault();
       
    if(prepareNewVertex){
             
        if(prepareNewVertex.type === 'vertex'){
            var positionMouse = getPositionMouse(event);
            factoryVertex(prepareNewVertex.value, positionMouse.x, positionMouse.y);
            generateVertexToString();
            prepareNewVertex = null;
        }
    }else if(prepareNewEdge){
        var selectedGroupVertex = getGroupVertexInteesect(event);

        if(selectedGroupVertex){

            groupVertexSelected = selectedGroupVertex;

            console.log(selectedGroupVertex);

            prepareNewEdge.vertice1 = selectedGroupVertex.name;
            
            if(!groupEdgeSelected){
                groupEdgeSelected = factoryEdge(prepareNewEdge, selectedGroupVertex.position.x, selectedGroupVertex.position.y);
            }
           
        }


    }

    render();
    
}

function onMouseMove(event){

    if(groupEdgeSelected){

        var edgesCalda = groupEdgeSelected.children[0];
        var positionMouse = getPositionMouse(event);
        edgesCalda.geometry.vertices[1].x = positionMouse.x;
        edgesCalda.geometry.vertices[1].y = positionMouse.y;
        edgesCalda.geometry.verticesNeedUpdate = true;

        render();

    }

}

function generateVertexToString(){

    var names = [];

    for(var i in groupsVertexs){
        names.push(groupsVertexs[i].name);
    }

    var str = '{'+names.join(',')+'}';

    var e = document.getElementById('vertice');
    e.value = str;

}

function generateEdgeToString(){

    var names = [];

    for(var i in groupsEdges){
        var edge = groupsEdges[i].std;
        names.push('('+edge.vertice1+','+edge.vertice2+','+edge.value+')');
    }

    var str = '{'+names.join('')+'}';

    var e = document.getElementById('arestas');
    e.value = str;
    
}

function onMouseUp(event){

    if(groupEdgeSelected){

        var selectedGroupVertex = getGroupVertexInteesect(event);

        if(selectedGroupVertex){
            var edgesCalda = groupEdgeSelected.children[0];
            edgesCalda.geometry.vertices[1].x = selectedGroupVertex.position.x;
            edgesCalda.geometry.vertices[1].y = selectedGroupVertex.position.y;
            edgesCalda.geometry.verticesNeedUpdate = true;

            groupEdgeSelected.std.vertice2 = selectedGroupVertex.name;
            prepareNewEdge = null;
            generateEdgeToString();
        }else{
            removeEdge(groupEdgeSelected);
        }

        groupEdgeSelected = null;

    }

    render();
}

function init(){
    
    renderer = new THREE.WebGLRenderer();
    renderer.setSize( canvasWidth, canvasHeight );
    renderer.setClearColor( 0xdddddd, 1);

    contentInterface.appendChild( renderer.domElement );

    scene = new THREE.Scene();

    camera = new THREE.PerspectiveCamera(
        45,             // Field of view
        canvasWidth / canvasHeight,      // Aspect ratio
        1,            // Near plane
        10000           // Far plane
    );
    camera.position.set(0, 0, 25);
    camera.lookAt( scene.position );  
    

    renderer.domElement.addEventListener('mousedown',   onMouseDown, false);
    renderer.domElement.addEventListener('mousemove',   onMouseMove, false);
    renderer.domElement.addEventListener('mouseup',     onMouseUp, false);
    
    render();
    
}

function render(){
    renderer.render( scene, camera );
}

function animate() {
        if(animation){
            requestAnimationFrame( animate );
        }

        render();

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function showInterface(){
    var interface = document.getElementById('painel-interface');
    interface.style.display = (interface.style.display!=='block')?'block':'none';
    return false;
}

function preencherIlhas(){
    document.getElementById('vertice').value = '{1,2,3,4,5,6,7,8}';
    document.getElementById('arestas').value = '{(1,2,240)(1,3,210)(1,4,340)(1,5,280)(1,6,200)(1,7,345)(1,8,120)(2,3,265)(2,4,175)(2,5,215)(2,6,180)(2,7,185)(2,8,155)(3,4,260)(3,5,115)(3,6,350)(3,7,435)(3,8,195)(4,5,160)(4,6,330)(4,7,295)(4,8,230)(5,6,360)(5,7,400)(5,8,170)(6,7,175)(6,8,205)(7,8,305)}';
    return false;
}