<template id="gameControls">
    <div class="content has-text-left is-dark is-small">
        <h2 class="title">Game Controls</h2>
        <div class="tag is-white icon is-small key-tag" :class="this.isPressed(38)"><i class="fa fa-caret-up"></i></div> ROTATE MINO<br>
        <div class="tag is-white icon is-small key-tag" :class="this.isPressed(40)"><i class="fa fa-caret-down"></i></div> MOVE DOWN<br>
        <div class="tag is-white icon is-small key-tag" :class="this.isPressed(37)"><i class="fa fa-caret-left"></i></div> MOVE LEFT<br>
        <div class="tag is-white icon is-small key-tag" :class="this.isPressed(39)"><i class="fa fa-caret-right"></i></div> MOVE RIGHT<br>
        <div class="tag is-white key-tag" :class="isPressed(32)"><small>SPACE</small></div> DROP<br>
    </div>
</template>
<script>
import Events from './Events.js';
export default {
    data(){
        return {
            keydown : null
        }
    },
    methods : {
        isPressed(keycode){
            return { 'is-warning' : this.keydown==keycode };
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
