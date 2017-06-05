<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tetrimino</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.2/css/bulma.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div id="app">
        <section class="hero is-dark is-bold is-fullheight">

            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title">
                        Tetrimino
                    </h1>
                    <h2 class="subtitle">
                        A Tetris Clone Game based on Vue.js
                    </h2>
                    <hr />
                    <stage></stage>
                </div>
            </div>
        </section>
    </div>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
