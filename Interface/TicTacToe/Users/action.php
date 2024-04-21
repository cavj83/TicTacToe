<link rel="stylesheet" href="gatostyle.css">
<script src="../../../libs/easyui/jquery.min.js" type="text/javascript"></script>
<body onload="init();">
    
    <main>
    <div class="turn-container">
        <h3>Turno</h3>
        <div class="turn-box align">X</div>
        <div class="turn-box aling">O</div>
        <div class="bg"></div>
    </div>
    <div class="main-grid">
        <div id="cell-0" class="box align">1</div>
        <div id="cell-1" class="box align">2</div>
        <div id="cell-2" class="box align">3</div>
        <div id="cell-3" class="box align">4</div>
        <div id="cell-4" class="box align">5</div>
        <div id="cell-5" class="box align">6</div>
        <div id="cell-6" class="box align">7</div>
        <div id="cell-7" class="box align">8</div>
        <div id="cell-8" class="box align">9</div>
    </div>
    
    <button id="play-again">Nuevo</button>
    <button id="back">Regresar</button>
    </main>
</body>
<script>
let boxes = document.querySelectorAll(".box");
const KEYCODE = {
    Left:37,
    Up:38,
    Right:39,
    Down: 40,
    Enter: 13,
    Return: 10009
};
const LEFT_MOVEMENT = -1;
const RIGHT_MOVEMENT = 1;
const UP_MOVEMENT = -3;
const DOWN_MOVEMENT = 3;
const MAX_LIMIT = 8;
const MIN_LIMIT = 0;
const NUMBER_OF_PLAYS_TO_WIN = 5;

const VICTORY_SCENARIOS = [
    [0,1,2],
    [3,4,5],
    [6,7,8],
    [0,3,6],
    [1,4,7],
    [2,5,8],
    [0,4,8],
    [2,4,6]
];
var PLAYER_WON = "";
var currentPosition = 0;
var gameContinues = true;
var gameBoardCells = ["","","","","","","","",""];
var playerInturn = "X";
var numberOfPlays = 0;


var init = function () {
    console.log('init() called');
    boxes.forEach(e=>{
        e.innerHTML = "";
        e.style.color = "#fff";
        e.classList.remove("focus");
    })
    document.addEventListener('keydown', function(e) {
    	switch(e.keyCode){
    	case KEYCODE.Left: 
            move(LEFT_MOVEMENT);
    		break;
    	case KEYCODE.Up:
            move(UP_MOVEMENT);
    		break;
    	case KEYCODE.Right:
            move(RIGHT_MOVEMENT);
    		break;
    	case KEYCODE.Down:
            move(DOWN_MOVEMENT);
    		break;
    	case KEYCODE.Enter:
            enterPressed();
    		break;
    	case KEYCODE.Return:
		    tizen.application.getCurrentApplication().exit();
    		break;
    	default:
    		console.log('Key code : ' + e.keyCode);
    		break;
    	}
    });
};
//window.onload = init;
function move(movement){
    if(!PLAYER_WON){
        var newPosition = currentPosition + movement;
        if(isMoveValid(newPosition)){
            changecellFocused(newPosition);
        }
    }
    
}

function isMoveValid(newPosition){
    return (MIN_LIMIT <=newPosition && newPosition <= MAX_LIMIT);
}

function changecellFocused(newPosition){
    document.getElementById("cell-"+currentPosition).classList.remove("focus");
    document.getElementById("cell-"+newPosition).classList.add("focus");
    currentPosition = newPosition;
}
function enterPressed(){
    numberOfPlays++;
    if(gameContinues){
        if(isFocusCellEmpty()){
            playTurn();
            compareVictory();
        }else{
            compareVictory();
        }
    }
}
function isFocusCellEmpty(){
    return gameBoardCells[currentPosition] == "";
}

function playTurn(){
    gameBoardCells[currentPosition] = playerInturn;
    drawBoard();
}
function drawBoard(){
    document.getElementById("cell-"+currentPosition).innerHTML = playerInturn;
    playerInturn = (playerInturn == "X" ? "0" : "X");
    if(playerInturn == "X"){
        document.querySelector(".bg").style.left = "0";
    }else{
        document.querySelector(".bg").style.left = "85px";
    }
}
function compareVictory(){
    console.log(victoryPossible());
    if(!victoryPossible()){
        return;
    }
    /* //Método de Fuerza Bruta
    for (let idVictoryScenadiro = 0; idVictoryScenadiro < VICTORY_SCENARIOS.length; idVictoryScenadiro++) {
        const victoryScenario = VICTORY_SCENARIOS[idVictoryScenadiro];
        if(notEmptyCell(gameBoardCells[victoryScenario[0]]) && areCellsEquals(victoryScenario)){
            gameWon();
        }
    }
    */
   //Método de 2 evaluaciones por ciclo.
    for(var i=0;i<4;i++){
        var x = i;  //[0,1,2,3]
        var y = 4;  //[4]
        var z = 8-i;//[8,7,6,5]
        if(gameBoardCells[x]!="" && gameBoardCells[z]!=""){
            if(gameBoardCells[x]==gameBoardCells[y] 
            && gameBoardCells[y]==gameBoardCells[z]){
                //console.log(x,y,z);
                PLAYER_WON = gameBoardCells[z];
                return gameWon([x,y,z]);
            } 
        }

        var z = 1 + (2 * i);  //[1,3,5,7]
        var x = (z<4)?0:8;  //[0,8]
        var y = (2*z)-x;    //[2,6]
        if(gameBoardCells[x] != "" && gameBoardCells[y] != "" && gameBoardCells[z] != ""){
            if(gameBoardCells[x] == gameBoardCells[z] 
            && gameBoardCells[z] == gameBoardCells[y]){
                PLAYER_WON = gameBoardCells[z];
                return gameWon([x,y,z]);
            }
                
        }
    }
}
function victoryPossible(){
    console.log(numberOfPlays);
    return numberOfPlays >= NUMBER_OF_PLAYS_TO_WIN;
}
function notEmptyCell(cell){
    return cell != "";
}
function areCellsEquals(victoryScenario){
    return (gameBoardCells[victoryScenario[0]]== gameBoardCells[victoryScenario[1]]
        && gameBoardCells[victoryScenario[0]]== gameBoardCells[victoryScenario[2]]);
}
function gameWon(row){
    alert("Ganador [ "+PLAYER_WON+" ]!");
    for(indice=0;indice<3;indice++){
        document.getElementById("cell-"+row[indice]).classList.add("focus");
    }
}
document.querySelector("#play-again").addEventListener("click",()=>{
    location.reload();
})
document.querySelector("#back").addEventListener("click",()=>{
    window.location.assign("../../../index.html");
})
</script>