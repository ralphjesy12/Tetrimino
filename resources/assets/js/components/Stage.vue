<template lang="html">
    <div class="stage">
        <div class="onboardtiles" :style="{ width : this.size.w * 32 + 'px' , height : this.size.h * 32 + 'px'  }">
            <div v-for="(mino,index) in this.onboard" class="tile onboard" :data-kind="mino.kind" :style="tileStyle(mino.x,mino.y)" v-show="mino.y>=0"></div>
        </div>
        <mino v-for="(mino,index) in this.minos" :x=mino.x :y=mino.y :kind=mino.kind :face=mino.face :key="mino.id" ></mino>
    </div>
</template>

<script>

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
            window.addEventListener('keydown', function(event) {
                event.preventDefault();
                switch (event.keyCode) {
                    case 67:  // C
                    console.log('Changing Mino');
                    self.createNewMino();
                    break;
                    case 32: // Drop
                    self.instantDrop();
                    break;
                    case 37: // Left
                    self.moveLeft();
                    break;
                    case 38: // Rotate
                    self.moveRotate();
                    break;
                    case 39: // Right
                    self.moveRight();
                    break;
                    case 40: // Down
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
            if(this.lastState !== null){
                this.minos[this.active] = this.lastState;
                this.lastState = null;
            }
        },
        moveLeft(){
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
            console.log('Instant Drop');
            while(this.moveDown()){
                console.log('Still safe');
            }
            console.log('Collided');
        },
        moveRotate(){
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
            console.info('Checking collisions');
            var activeMino = this.minos[this.active];
            var activeMinoTiles = tiles[activeMino.kind][activeMino.face];
            for (var row = 0; row < activeMinoTiles.length; row++) {
                for (var col = 0; col < activeMinoTiles[row].length; col++) {
                    if(activeMinoTiles[row][col]){

                        if((col+activeMino.x < 0) || (col+activeMino.x >= this.size.w)){

                            console.log('Mino Collided');
                            return false;

                        }

                        if((row+activeMino.y >= this.size.h)){

                            // Block Reached the Bottom of the Board
                            return false;

                        }

                        // Check if mino, hits one from the onboard tiles

                        for (var i = 0; i < this.onboard.length; i++) {

                            if((row+activeMino.y == this.onboard[i].y) && (col+activeMino.x == this.onboard[i].x)){
                                // Mino Collided with Onboard Tile
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
            }

            self.moveDown();

        },self.game.speed);

    }
}
</script>
