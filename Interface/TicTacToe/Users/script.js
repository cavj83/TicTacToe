
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

var currentPosition = 0;
var gameContinues = true;
var gameBoardCells = ["","","","","","","","",""];
var playerInturn = "X";
var numberOfPlays = 5;


var init = function () {
    console.log('init() called');
 
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

window.onload = init;

function move(movement){
    var newPosition = currentPosition + movement;
    if(isMoveValid(newPosition)){
        changecellFocused(newPosition);
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
}
function compareVictory(){
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
   if(gameBoardCells[4]!=""){
        for(var i=0;i<4;i++){
            var x = i;  //[0,1,2,3]
            var y = 4;  //[4]
            var z = 8-i;//[8,7,6,5]
            if(gameBoardCells[x]!="" && gameBoardCells[z]!=""){
                if(gameBoardCells[x]==gameBoardCells[y] 
                && gameBoardCells[y]==gameBoardCells[z]) return gameWon(gameBoardCells[y]);
            }

            var z = 1 + (2 * i);  //[1,3,5,7]
            var x = (z<4)?0:8;  //[0,8]
            var y = (2*z)-x;    //[2,6]
            if(gameBoardCells[x] != "" && gameBoardCells[y] != "" && gameBoardCells[z] != ""){
                if(gameBoardCells[x] == gameBoardCells[z] 
                && gameBoardCells[z] == gameBoardCells[y]) return gameWon(gameBoardCells[z]);
            }
        }
   }
}
function victoryPossible(){
    return numberOfPlays >= NUMBER_OF_PLAYS_TO_WIN;
}
function notEmptyCell(cell){
    return cell != "";
}
function areCellsEquals(victoryScenario){
    return (gameBoardCells[victoryScenario[0]]== gameBoardCells[victoryScenario[1]]
        && gameBoardCells[victoryScenario[0]]== gameBoardCells[victoryScenario[2]]);
}
function gameWon(user){
    alert("Ganador [ "+user+" ]!");
    window.location.assign("../../../index.html");
}