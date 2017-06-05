<template lang="html">
    <div class="mino" :style="minoStyle(x,y)" :data-kind="this.kind">
        <template v-for="(tilerow,y) in this.tiles[this.kind][this.face]">
            <template v-for="(tile,x) in tilerow">
                <template v-if="tile">
                    <div class="tile" :style="tileStyle(x,y)"></div>
                </template>
            </template>
        </template>
    </div>
</template>

<script>
export default {
    data(){
        return {
            size : {
                w : 32,
                h : 32,
                x : 4,
                y : 4
            },
            tiles : [
                [
                    [
                        [ 0 , 0 , 0 , 0 ],
                        [ 0 , 1 , 1 , 0 ],
                        [ 0 , 1 , 1 , 0 ],
                        [ 0 , 0 , 0 , 0 ],
                    ],
                ],
                [
                    [
                        [ 0 , 0 , 0 , 0 ],
                        [ 0 , 0 , 1 , 0 ],
                        [ 0 , 1 , 1 , 0 ],
                        [ 0 , 1 , 0 , 0 ],
                    ],
                ],
                [
                    [
                        [ 0 , 0 , 0 , 0 ],
                        [ 0 , 1 , 0 , 0 ],
                        [ 0 , 1 , 1 , 0 ],
                        [ 0 , 0 , 1 , 0 ],
                    ],
                ],
                [
                    [
                        [ 0 , 0 , 0 , 0 ],
                        [ 0 , 1 , 0 , 0 ],
                        [ 1 , 1 , 1 , 0 ],
                        [ 0 , 0 , 0 , 0 ],
                    ],
                ],
                [
                    [
                        [ 0 , 0 , 0 , 0 ],
                        [ 0 , 1 , 0 , 0 ],
                        [ 0 , 1 , 0 , 0 ],
                        [ 0 , 1 , 1 , 0 ],
                    ],
                ],
                [
                    [
                        [ 0 , 0 , 0 , 0 ],
                        [ 0 , 0 , 1 , 0 ],
                        [ 0 , 0 , 1 , 0 ],
                        [ 0 , 1 , 1 , 0 ],
                    ],
                ],
                [
                    [
                        [ 0 , 0 , 1 , 0 ],
                        [ 0 , 0 , 1 , 0 ],
                        [ 0 , 0 , 1 , 0 ],
                        [ 0 , 0 , 1 , 0 ],
                    ],
                ],
            ]
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
            default : 0
        },
        y : {
            type : Number,
            default : 0
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

<style lang="scss">
.mino{
    position: absolute;

    .tile{
        position: absolute;
        width: 32px;
        height: 32px;
    }

    @mixin tileColor($image,$color){
        .tile {
            background: url($image),$color;
            background-blend-mode: multiply;
            background-size: cover;
        }
    }

    &[data-kind="0"]{ @include tileColor("/img/mino.png",#008eff); }
    &[data-kind="1"]{ @include tileColor("/img/mino.png",#00ff94); }
    &[data-kind="2"]{ @include tileColor("/img/mino.png",#f1ff00); }
    &[data-kind="3"]{ @include tileColor("/img/mino.png",#ff1818); }
    &[data-kind="4"]{ @include tileColor("/img/mino.png",#ff00f9); }
    &[data-kind="5"]{ @include tileColor("/img/mino.png",#bf00ff); }
    &[data-kind="6"]{ @include tileColor("/img/mino.png",#bf00ff); }
}
</style>
