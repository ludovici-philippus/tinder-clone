<?php 
    if(isset($_GET['deslogar'])){
        Usuarios::deslogar();
    }
    else if(isset($_GET['action'])){
        $id = $_GET['id'];
        $action = $_GET['action'];
        $sql = MySql::connect()->prepare("SELECT * FROM `likes` WHERE user_to = ? AND user_from = ?");
        $sql->execute(array($_SESSION['id'], $id));
        if($sql->rowCount() == 0){
            $sql = MySql::connect()->prepare("INSERT INTO `likes` VALUES (null, ?, ?, ?)");
            $sql->execute(array($_SESSION['id'], $id, $action));
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo(a) <?php echo $_SESSION['nome']; ?></title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,body{
            height: 100%;
        }

        .sidebar{
            float: left;
            width: 300px;
            height: 100%;
            background-color: rgb(230, 230, 230);
        }

        .topo{
            padding: 10px;
            background-color: #e82975;
            color: white;
        }

        .topo a{
            color: white;
        }

        .btn-coord{
            text-align: center;
        }

        .btn-coord button{
            background-color: #e82975;
            padding: 8px 15px;
            color: white;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            font-weight: bold;
            border: 0;
            cursor: pointer;
        }

        .info-localizacao{
            padding: 10px;
        }

        .usuario-like{
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            float: left;
            width: calc(100%-300px);
            width: 300px;
            height: 300px;
            text-align: center;
            background-color: #ccc;
        }

        .usuario-like h2{
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .usuario-like p:nth-of-type(1){
            position: absolute;
            bottom: 0;
            left: 0;
        }

        .usuario-like p:nth-of-type(2){
            position: absolute;
            bottom: 0;
            right: 0;
        }

        .clear{
            clear: both;
        }

    </style>
</head>
<body>
    <div class="sidebar">
        <div class="topo">
            <h3>Bem-vindo(a) <?= $_SESSION['nome']; ?> | <a href="<?= INCLUDE_PATH;?>?deslogar">Deslogar!</a></h3>
        </div>
        <div class="btn-coord">
            <button onclick="getLocation()">Atualizar Coordenadas!</button>
        </div>
        <div id="localizacao" class="info-localizacao">
            <p class="lat-text">Latitude: <?= $_SESSION['latitude']; ?></p>
            <p class="long-text">Longitude: <?= $_SESSION['longitude']; ?></p>
            <p>Localização: <?= $_SESSION['localizacao']; ?></p>
            <br>
            <h3>Seus Crushs: </h3>
            <ul>
                <?php 
                    $crushs = Usuarios::pega_crushs($_SESSION['id']);
                    foreach ($crushs as $key => $value) {
                ?>
                    <li>
                        <?= $value['nome'];?> | Distância: <span class="user-distancia"></span>
                        <span style="display: none;" class="lat-user"><?= $value['lat_coord'];?></span>
                        <span style="display: none;" class="long-user"><?= $value['long_coord'];?></span>
                    </li>
                <?php }?>
            </ul>
        </div>
    </div>

    <div class="usuario-like">
        <div class="box-usuario-nome">
            <?php 
                $usuario = Usuarios::pega_usuario_novo();
            ?>
            <h2><?= $usuario['nome']; ?></h2>
            <p><a href="?action=1&id=<?= $usuario['id'];?>">Gostei!</a></p>
            <p><a href="?action=0&id=<?= $usuario['id'];?>">Não gostei!</a></p>
        </div>
    </div>
    <div class="clear"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        }
    }

    function showPosition(position) {
        $(".lat-text").html("Latitude: " + position.coords.latitude);
        $(".long-text").html("Longitude: " + position.coords.longitude);
        update_coords(position.coords.latitude, position.coords.longitude);
    }

    function update_coords(lat_par, long_par){
        $.ajax({
            url:"atualizar-coord.php",
            method:"POST",
            data:{latitude:lat_par, longitude:long_par}
        }).done(function(data){
            alert("Atualizado com sucesso!");
            console.log(data);
        })
    }
    
    $(function(){

        var my_lat = <?= $_SESSION['latitude'];?>;
        var my_long = <?= $_SESSION['longitude'];?>;
        console.log(my_lat);
        $('li').each(function(){
            var coord_lat = $(this).find('.lat-user').html();
            var coord_long = $(this).find(".long-user").html();
            var distance = Math.round(getDistanceFromLatLonInKm(my_lat, my_long, coord_lat, coord_long)*100)/100;
            $(this).find(".user-distancia").html(distance+" Km");
            
        })
        function getDistanceFromLatLonInKm(lat1,lon1,lat2,lon2) {
            var R = 6371; // Radius of the earth in km
            var dLat = deg2rad(lat2-lat1);  // deg2rad below
            var dLon = deg2rad(lon2-lon1); 
            var a = 
                Math.sin(dLat/2) * Math.sin(dLat/2) +
                Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
                Math.sin(dLon/2) * Math.sin(dLon/2)
                ; 
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
            var d = R * c; // Distance in km
            return d;
         }

        function deg2rad(deg) {
            return deg * (Math.PI/180)
        }
    });
    </script>
    
</body>
</html>