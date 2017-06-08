<template lang="html">
    <div class="stage">
        <div class="onboardtiles" :style="{ width : this.size.w * 32 + 'px' , height : this.size.h * 32 + 'px'  }">
            <div v-for="(mino,index) in this.onboard" class="tile onboard" :data-kind="mino.kind" :style="tileStyle(mino.x,mino.y)" v-show="mino.y>=0"></div>
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
                isPaused : false,
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
                y :  -3,
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
        },
        stopGame(){
            clearInterval(this.game.timerInterval);
            this.game.isOver = true;
            console.log('Game is Over');
        }
    },
    components : {
        mino
    },
    mounted(){
        var self = this;
        // Create one mino on center
        self.minos = [];
        self.registerKeyboardMapping();
        self.createNewMino();

        self.game.timerInterval = setInterval(function(){
            if(self.game.isPaused || self.game.isOver){
                clearInterval(self.game.timerInterval);
            }else{
                self.moveDown();
            }
        },self.game.speed);

    }
}
</script>
