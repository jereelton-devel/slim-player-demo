<!DOCTYPE html>
<html>
<head>
	<title>Slim Player</title>

    <link rel="stylesheet" href="./css/font-awesome.min.css" type="text/css" />
    <link rel="stylesheet" href="./css/bootstrap.min.css" type="text/css" />
    <link rel="stylesheet" href="./css/styles.css" type="text/css" />

</head>
<body>

<div id="topo">
    <input class="btn btn-success" type="button" name="" id="bt-start-player" value="Start" />
    <input class="btn btn-default" type="button" name="" id="bt-stop-player" value="Stop" disabled />
    <input class="btn btn-danger" type="button" name="" id="bt-reset-player" value="Reset" disabled />
</div>
<div id="view">

    <div id="subview"></div>

    <!--Resultados-->
    <table class="table text-center" id="tb_slim_player">
        <thead>
            <th class="text-center">ID</th>
            <th class="text-center">PLAYER 1</th>
            <th class="text-center">MACHINE PLAYER</th>
            <th class="text-center">PLAYER 2</th>
            <th class="text-center">WINNER</th>
        </thead>
        <tbody id="tbody_slim_player">

        </tbody>
    </table>
</div>

<script src="js/vendor/jquery/jquery-1.11.3.js"></script>
<script>

$(document).ready(function(){

    var controlPlayer = null;

    $("#tb_slim_player").hide();

    function getPlayerResult() {

        $.ajax({
            type: "GET",
            url: "http://localhost/webdev/testes/slim-player-demo/api3/info/player-status",
            data: "action=player-status",
            dataType: "text",
            async: false,
            success: function(resp) {
                if(resp === 0) {

                    clearInterval(controlPlayer);
                    $("#subview").html("O PLAYER NAO ESTA EXECUTANDO...");
                    console.log(resp);

                } else {

                    $.ajax({
                        type: "GET",
                        url: "http://localhost/webdev/testes/slim-player-demo/api3/info/player-result",
                        data: "action=player-result",
                        dataType: "text",
                        success: function(resp) {

                            resp = JSON.parse(resp);

                            if(resp.winner == 'api1') {
                                playerWin = 'player 1';
                            } else if(resp.winner == 'api2') {
                                playerWin = 'player 2';
                            } else {
                                playerWin = resp.winner;
                            }

                            winner1 = (resp.winner == 'api1') ? 'winner' : '';
                            winner2 = (resp.winner == 'api2') ? 'winner' : '';

                            $("#tbody_slim_player").append("" +
                                "<tr>" +
                                    "<td>"+resp.id+"</td>" +
                                    "<td class='"+winner1+"'>"+resp.api1_val+"</td>" +
                                    "<td>"+resp.api3_val+"</td>" +
                                    "<td class='"+winner2+"'>"+resp.api2_val+"</td>" +
                                    "<td class='text-uppercase'>"+playerWin+"" +
                                "</tr>");

                            //console.log(typeof resp, resp);
                        }
                    });

                }
            }
        });
    }

    $("#bt-start-player").on('click', function() {

        $("#tb_slim_player").removeClass('hide');
        $("#tb_slim_player").show();

        $("#subview").html("WELCOME TO SLIM PLAYER");

        $.ajax({
            type: "GET",
            url: "http://localhost/webdev/testes/slim-player-demo/api3/controll/player-start",
            data: "action=player-start",
            dataType: "text",
            success: function(resp) {
                if(resp.search('Erro:') === -1) {
                    $("#subview").html(resp);
                    //console.log(resp);
                } else {
                    $("#subview").html(resp);
                    return false;
                }
            }
        });

        controlPlayer = setInterval(getPlayerResult, 3000);
        $("#bt-start-player").prop('disabled', true);
        $("#bt-stop-player").prop('disabled', false);
        $("#bt-reset-player").prop('disabled', true);

    });

    $("#bt-stop-player").on('click', function() {
        if(confirm("Deseja cancelar o jogo ?")) {
            $.ajax({
                type: "GET",
                url: "http://localhost/webdev/testes/slim-player-demo/api3/controll/player-stop",
                data: "action=player-stop",
                dataType: "text",
                success: function(resp) {

                    setTimeout(function() {
                        clearInterval(controlPlayer);
                        $("#subview").html(resp);
                        $("#bt-start-player").prop('disabled', false);
                        $("#bt-stop-player").prop('disabled', true);
                        $("#bt-reset-player").prop('disabled', false);
                        //console.log(resp);
                    }, 3000);

                }
            });
        }
    });

    $("#bt-reset-player").on('click', function() {
        if(confirm("Deseja resetar o jogo ?")) {

            $.ajax({
                type: "GET",
                url: "http://localhost/webdev/testes/slim-player-demo/api3/controll/player-reset",
                data: "action=player-reset",
                dataType: "text",
                success: function(resp) {

                    $.ajax({
                        type: "GET",
                        url: "http://localhost/webdev/testes/slim-player-demo/api2/controll/player-reset",
                        data: "action=player-reset",
                        dataType: "text",
                        async: false,
                        success: function(resp) {

                            $.ajax({
                                type: "GET",
                                url: "http://localhost/webdev/testes/slim-player-demo/api1/controll/player-reset",
                                data: "action=player-reset",
                                dataType: "text",
                                async: false,
                                success: function(resp) {
                                }
                            });
                        }
                    });

                    $("#subview").html(resp);
                    $("#tbody_slim_player").html("");
                    $("#tb_slim_player").hide();
                    $("#bt-start-player").prop('disabled', false);
                    $("#bt-stop-player").prop('disabled', true);
                    $("#bt-reset-player").prop('disabled', true);
                }
            });
        }
    });
});

</script>

</body>
</html>