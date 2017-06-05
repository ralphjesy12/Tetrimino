<template lang="html">
    <div class="stage">
        <mino v-for="(mino,index) in this.minos" :x=mino.x :y=mino.y :kind=mino.kind :key="mino.id"></mino>
    </div>
</template>

<script>

import mino from './Mino.vue';

export default {
    data(){
        return {
            size : {
                w : 15,
                h : 20
            },
            minos : []
        }
    },
    methods: {
        getRandomInt(min, max) {
            min = Math.ceil(min);
            max = Math.floor(max);
            return Math.floor(Math.random() * (max - min)) + min;
        }
    },
    components : {
        mino
    },
    created(){
        var self = this;
        // Create one mino on center
        self.minos = [{
            x : Math.floor(self.size.w / 2) - 2,
            y : Math.floor(self.size.h / 2) - 2,
            kind : self.getRandomInt(0,7)
        }];

        window.addEventListener('keydown', function(event) {
            event.preventDefault();
            switch (event.keyCode) {
                case 32:

                // Change Minos
                self.minos = [{
                    x : Math.floor(self.size.w / 2) - 2,
                    y : Math.floor(self.size.h / 2) - 2,
                    kind : self.getRandomInt(0,7)
                }];

                break;
                case 37:
                self.minos[0].x--;
                break;
                case 38:
                self.minos[0].y--;
                break;
                case 39:
                self.minos[0].x++;
                break;
                case 40:
                self.minos[0].y++;
                break;
                default:

            }
        });

    }
}
</script>

<style lang="less">
.stage{
    width: 480px;
    height: 650px;
    border: 1px solid #bbb;
    position: relative;
    display: block;
    margin: 0 auto;
    margin-top: 10px;
    background: #fff;

    // Checkered BG
    background-image:
    -moz-linear-gradient(45deg, #BBB 25%, transparent 25%),
    -moz-linear-gradient(-45deg, #BBB 25%, transparent 25%),
    -moz-linear-gradient(45deg, transparent 75%, #BBB 75%),
    -moz-linear-gradient(-45deg, transparent 75%, #BBB 75%);
    background-image:
    -webkit-gradient(linear, 0 100%, 100% 0, color-stop(.26, #BBB), color-stop(.26, transparent)),
    -webkit-gradient(linear, 0 0, 100% 100%, color-stop(.26, #BBB), color-stop(.26, transparent)),
    -webkit-gradient(linear, 0 100%, 100% 0, color-stop(.76, transparent), color-stop(.76, #BBB)),
    -webkit-gradient(linear, 0 0, 100% 100%, color-stop(.76, transparent), color-stop(.76, #BBB));

    -moz-background-size:64px 64px;
    background-size:64px 64px;
    -webkit-background-size: 64px 64px;
    background-position: 0 0, 32px 0, 32px -32px, 0px 32px;

}
</style>
