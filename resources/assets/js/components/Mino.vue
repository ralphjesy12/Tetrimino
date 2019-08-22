<template>
    <div class="mino" :style="minoStyle(x,y)" :data-kind="this.kind">
        <template v-for="(tilerow,yy) in this.tiles[this.kind][this.face]">
            <template v-for="(tile,xx) in tilerow">
                <template v-if="tile">
                    <div v-show="(yy+y)>=0" class="tile" :style="tileStyle(xx,yy)"></div>
                </template>
            </template>
        </template>
    </div>
</template>

<script>

import tiles from './Tiles.js'

export default {
    data(){
        return {
            size : {
                w : 32,
                h : 32,
                x : 4,
                y : 4
            },
            tiles : tiles
        }
    },
    computed : {
        posx() {
            return this.x * this.size.w;
        },
        posy() {
            return this.y * this.size.h;
        },
    },
    methods : {
        minoStyle(x,y){
            return {
                width: (this.size.w * this.size.x) + 'px'  ,
                height : (this.size.h * this.size.y) + 'px',
                top: ( y * this.size.h ) + 'px'  ,
                left : ( x * this.size.w ) + 'px'
            }
        },
        tileStyle(x,y){
            return {
                top : (y * this.size.h) + 'px',
                left : (x * this.size.w) + 'px',
            }
        },
    },
    props : {
        x : {
            type : Number,
            default : 0,
            required : true
        },
        y : {
            type : Number,
            default : 0,
            required : true
        },
        kind : {
            type : Number,
            default : 0
        },
        face : {
            type : Number,
            default : 0
        }
    },
    created(){

    },
    mounted(){
    }
}
</script>
