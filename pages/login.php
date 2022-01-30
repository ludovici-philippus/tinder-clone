<?php 
    if(isset($_SESSION['login'])){
        header("Location: ".INCLUDE_PATH."home");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        form{
            max-width: 800px;
            padding: 10px;
            border: 2px solid rgb(230, 230, 230);
            border-radius: 10px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        input:not([type=submit]){
            width: 100%;
            height: 40px;
            border: 1px solid #ccc;
            padding-left: 4px;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <?php 
        if(isset($_POST['acao'])){
            if(Usuarios::verificar_login($_POST['login'], $_POST['senha'])){
                $get_id = Usuarios::get_id($_POST['login']);
                
                Usuarios::start_session($_POST['login'], $get_id);
                header("Location: ".INCLUDE_PATH."home");
            }
            else
                header("Location: ".INCLUDE_PATH."login");
        }
    ?>
    <form method="post">
        <h2>Login: </h2>
        <input type="text" name="login">
        <input type="password" name="senha">
        <input type="submit" value="Enviar" name="acao">
    </form>
</body>
</html>