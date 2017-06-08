<template id="gameControls">
    <div class="content has-text-left is-dark is-small">
        <nav class="level is-mobile">
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Time Elapsed</p>
                    <p class="title">01:30</p>
                </div>
            </div>
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Time Left</p>
                    <p class="title">00:34</p>
                </div>
            </div>
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Lines Sent</p>
                    <p class="title">456K</p>
                </div>
            </div>
            <div class="level-item has-text-centered">
                <div>
                    <p class="heading">Score</p>
                    <p class="title">789</p>
                </div>
            </div>
        </nav>
        <h2 class="title">Game Controls</h2>
        <div class="columns">
            <div class="column">
                <div class="field has-addons">
                    <p class="control">
                        <a class="button" @click="startGame" v-if="!game.isStarted">
                            <span class="icon is-small">
                                <i class="fa fa-play"></i>
                            </span>
                            <span>Start</span>
                        </a>
                        <a class="button" @click="continueGame" v-if="game.isStarted" :disabled ="!game.isPaused">
                            <span class="icon is-small">
                                <i class="fa fa-play"></i>
                            </span>
                            <span>Continue</span>
                        </a>
                    </p>
                    <p class="control">
                        <a class="button" @click="pauseGame" :disabled="(game.isPaused || !game.isStarted)">
                            <span class="icon is-small">
                                <i class="fa fa-pause"></i>
                            </span>
                            <span>Pause</span>
                        </a>
                    </p>
                </div>
            </div>
            <div class="column">
                <div class="tag is-white icon is-small key-tag" :class="this.isPressed(38)"><i class="fa fa-caret-up"></i></div> ROTATE MINO<br>
                <div class="tag is-white icon is-small key-tag" :class="this.isPressed(40)"><i class="fa fa-caret-down"></i></div> MOVE DOWN<br>
                <div class="tag is-white icon is-small key-tag" :class="this.isPressed(37)"><i class="fa fa-caret-left"></i></div> MOVE LEFT<br>
                <div class="tag is-white icon is-small key-tag" :class="this.isPressed(39)"><i class="fa fa-caret-right"></i></div> MOVE RIGHT<br>
                <div class="tag is-white key-tag" :class="isPressed(32)"><small>SPACE</small></div> DROP<br>
            </div>
        </div>
    </div>
</template>
<script>
import Events from './Events.js';
export default {
    data(){
        return {
            game : {
                isStarted : false,
                isPaused : true,
                isOver : false,
            },
            keydown : null
        }
    },
    methods : {
        isPressed(keycode){
            return { 'is-warning' : this.keydown==keycode };
        },
        startGame(){
            this.game.isStarted = true;
            this.game.isPaused = false;
            Events.$emit('startGame');
        },
        continueGame(){
            this.game.isPaused = false;
            Events.$emit('continueGame');
        },
        pauseGame(){
            this.game.isPaused = true;
            Events.$emit('pauseGame');
        }
    },
    created(){
        var self = this;


        Events.$on('gameHasKeyDown',function(keyCode){
            self.keydown = keyCode;
        });

        Events.$on('gameHasKeyUp',function(keyCode){
            self.keydown = null;
        });
    }
}
</script>
