<template>
    <div class="stage">
        <div class="onboardtiles" :style="{ width : this.size.w * 32 + 'px' , height : this.size.h * 32 + 'px'  }">
            <div v-for="(mino,index) in this.onboard" class="tile onboard" :data-kind="mino.kind" :style="tileStyle(mino.x,mino.y)" v-show="mino.y>=0" :class="{ 'pulse' : mino.sent }"></div>
        </div>
        <mino v-for="(mino,index) in this.minos" :x=mino.x :y=mino.y :kind=mino.kind :face=mino.face :key="mino.id" ></mino>
    </div>
</template>

<script>

import Events from './Events.js';
import mino from './Mino.vue';
import tiles from './Tiles.js'

export default {
    data(){
        return {
            game : {
                isPaused : true,
                isOver : false,
                speed : 1000,
                timerInterval : null
            },
            size : {
                w : 15,
                h : 20
            },
            minos : [],
            onboard : [],
            active : 0,
            lastState : null
        }
    },
    methods: {
        tileStyle(x,y){
            return {
                top : (y * 32) + 'px',
                left : (x * 32) + 'px',
            }
        },
        getRandomInt(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min)) + min;
        },
        registerKeyboardMapping(){
            console.info('Registering Keyboard Maps');
            var self = this;

            window.addEventListener('keyup', function(event) {
                event.preventDefault();
                Events.$emit('gameHasKeyUp',event.keyCode);
            });
            window.addEventListener('keydown', function(event) {
                event.preventDefault();

                Events.$emit('gameHasKeyDown',event.keyCode);

                switch (event.keyCode) {
                    case 67:  // C
                    console.log('Changing Mino');
                    if(self.game.isPaused || self.game.isOver) return false;
                    self.createNewMino();
                    break;
                    case 32: // Drop
                    if(self.game.isPaused || self.game.isOver) return false;
                    self.instantDrop();
                    break;
                    case 37: // Left
                    if(self.game.isPaused || self.game.isOver) return false;
                    self.moveLeft();
                    break;
                    case 38: // Rotate
                    if(self.game.isPaused || self.game.isOver) return false;
                    self.moveRotate();
                    break;
                    case 39: // Right
                    if(self.game.isPaused || self.game.isOver) return false;
                    self.moveRight();
                    break;
                    case 40: // Down
                    if(self.game.isPaused || self.game.isOver) return false;
                    self.moveDown();
                    break;
                    default:
                }

            });
        },
        saveState(){
            this.lastState = {
                x : this.minos[this.active].x,
                y : this.minos[this.active].y,
                kind : this.minos[this.active].kind,
                face : this.minos[this.active].face
            };
        },
        revertState(){
            console.log('Reverting State');
            if(this.lastState !== null){
                this.minos[this.active] = this.lastState;
                this.lastState = null;
            }
        },
        moveLeft(){
            if(this.game.isOver) return false;
            console.log('Move Left');
            this.saveState();
            this.minos[this.active].x--;
            if(!this.checkCollisions()){
                this.revertState();
                return false;
            }
            return true;
        },
        moveRight(){
            if(this.game.isOver) return false;
            console.log('Move Right');
            this.saveState();
            this.minos[this.active].x++;
            if(!this.checkCollisions()){
                this.revertState();
                return false;
            }
            return true;
        },
        moveDown(){
            if(this.game.isOver) return false;
            console.log('Move Down');

            this.saveState();
            this.minos[this.active].y++;
            if(!this.checkCollisions()){
                this.revertState();
                this.addToBoard();
                this.createNewMino();
                return false;
            }
            return true;
        },
        instantDrop(){
            if(this.game.isOver) return false;
            console.log('Instant Drop');
            while(this.moveDown()){
                console.log('Still safe');
            }
            console.log('Collided');
        },
        moveRotate(){
            if(this.game.isOver) return false;
            console.log('Rotate Mino');
            this.saveState();
            if(++this.minos[this.active].face > 3) this.minos[this.active].face = 0;
            if(!this.checkCollisions()){
                this.revertState();
                return false;
            }
            return true;
        },
        createNewMino(){

            if(this.game.isOver) return false;

            console.info('Creating Mino');

            this.lastState = null;
            this.minos = [];
            this.minos.push({
                x : Math.floor(this.size.w / 2) - 2,
                y :  -4,
                kind : this.getRandomInt(0,7),
                face : this.getRandomInt(0,4)
            });
            this.active = this.minos.length - 1;
        },
        checkCollisions(){
            if(this.game.isOver) return false;
            console.info('Checking collisions');

            var activeMino = this.minos[this.active];
            var activeMinoTiles = tiles[activeMino.kind][activeMino.face];
            for (var row = 0; row < activeMinoTiles.length; row++) {
                for (var col = 0; col < activeMinoTiles[row].length; col++) {
                    if(activeMinoTiles[row][col]){

                        if((col+activeMino.x < 0) || (col+activeMino.x >= this.size.w)){

                            console.log('Mino will collide with Board');
                            return false;

                        }

                        if((row+activeMino.y >= this.size.h)){
                            console.log('Mino will reach Bottom');
                            // Block Reached the Bottom of the Board
                            return false;

                        }

                        if((row+activeMino.y < 0) && !this.moveDown()){
                            // try to move down
                            console.log('Mino Overflowed');
                            this.stopGame();

                            return false;

                        }

                        // Check if mino, hits one from the onboard tiles

                        for (var i = 0; i < this.onboard.length; i++) {

                            if((row+activeMino.y == this.onboard[i].y) && (col+activeMino.x == this.onboard[i].x)){
                                // Mino Collided with Onboard Tile
                                console.log('Mino will collide with a Tile');
                                return false;

                            }
                        }

                    }
                }
            }
            console.log('Mino Safe');


            return true;
        },
        addToBoard(){
            var activeMino = this.minos[this.active];
            var activeMinoTiles = tiles[activeMino.kind][activeMino.face];
            for (var row = 0; row < activeMinoTiles.length; row++) {
                for (var col = 0; col < activeMinoTiles[row].length; col++) {
                    if(activeMinoTiles[row][col]){
                        this.onboard.push({
                            x: col+activeMino.x,
                            y: row+activeMino.y,
                            kind: activeMino.kind
                        });
                    }
                }
            }

            // After adding to board, check for a complete line
            console.log('Check lines');
            this.checkLines();
        },
        checkLines(){
            var self = this;
            var row = [];
            for (let i = 0; i < this.onboard.length; i++) {
                if(typeof row[this.onboard[i].y] == 'undefined')
                    row[this.onboard[i].y] = 0;
                row[this.onboard[i].y]++;
            }

            var linesremoved = [];
            for (let i = 0; i < row.length; i++) {
                if(typeof row[i]!= 'undefined' && row[i] == this.size.w){
                    linesremoved.push(i);
                    this.clearLine(i);
                }
            }

            if(linesremoved.length > 0){
                setTimeout(function() {
                    for (var j = 0; j < linesremoved.length; j++) {
                        for (let i = 0; i < self.onboard.length; i++) {
                            if(self.onboard[i].y < (linesremoved[j] + j))
                                self.onboard[i].y++;
                        }
                    }
                },1000);
            }

        },
        clearLine(row){
            var self = this;
            for (var i = 0; i < this.onboard.length; i++) {
                if(this.onboard[i].y == row){
                    this.onboard[i].sent = true;
                }
            }

            setTimeout(function(){

                for (var i = self.onboard.length - 1; i >= 0; i--) {
                    if(self.onboard[i].sent && self.onboard[i].sent == true){
                        self.onboard.splice(i,1);
                    }
                }

            },1000);
        },
        stopGame(){
            clearInterval(this.game.timerInterval);
            this.game.isOver = true;
            this.game.isPaused = false;
            Events.$emit('gameHasStopped');
            console.log('Game is Over');
        },
        pauseGame(){
            clearInterval(this.game.timerInterval);
            this.game.isPaused = true;
        },
        continueGame(){
            var self = this;
            this.game.isPaused = false;
            this.game.timerInterval = setInterval(function(){
                if(self.game.isPaused || self.game.isOver){
                    clearInterval(self.game.timerInterval);
                }else{
                    self.moveDown();
                }
            },this.game.speed);
        },
        startGame(){
            var self = this;
            this.minos = [];
            this.onboard = [];
            this.active = 0;
            this.game.isPaused = false;
            this.game.isOver = false;
            this.createNewMino();
            this.game.timerInterval = setInterval(function(){
                if(self.game.isPaused || self.game.isOver){
                    clearInterval(self.game.timerInterval);
                }else{
                    self.moveDown();
                }
            },this.game.speed);
        },
        registerEvents(){
            var self = this;
            Events.$on('startGame',function(){
                self.startGame();
            });
            Events.$on('pauseGame',function(){
                self.pauseGame();
            });
            Events.$on('continueGame',function(){
                self.continueGame();
            });
        }
    },
    components : {
        mino
    },
    mounted(){
        var self = this;
        // Create one mino on center
        self.registerKeyboardMapping();
        self.registerEvents();
    }
}
</script>
