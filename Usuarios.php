<?php 
    class Usuarios{
        public static function verificar_login($login, $senha){
            $verifica = MySql::connect()->prepare("SELECT * FROM `usuarios` WHERE email = ? AND senha = ?");
            $verifica->execute(array($login, $senha));

            if($verifica->rowCount() == 1) 
                return true;
            return false;
        }
        
        public static function start_session($login, $id){
            $_SESSION['login'] = $login;
            $_SESSION['id'] = $id;
            $sql = MySql::connect()->prepare("SELECT * FROM `usuarios` WHERE id = ?");
            $sql->execute(array($id));
            $info = $sql->fetch();
            $_SESSION['nome'] = $info["nome"];
            $_SESSION['localizacao'] = $info['localizacao'];
            $_SESSION['latitude'] = $info['lat_coord'];
            $_SESSION['longitude'] = $info['long_coord'];
            $_SESSION['sexo'] = $info['sexo'];

        }

        public static function get_id($login){
            $sql = MySql::connect()->prepare("SELECT id FROM `usuarios` WHERE email = ?");
            $sql->execute(array($login));
            $id = $sql->fetch()['id'];
            return $id; 
        }

        public static function deslogar(){
            session_destroy();
            header("Location: ".INCLUDE_PATH);
            die();
        }

        public static function pega_usuario_novo(){
            $sql = MySql::connect()->prepare("SELECT * FROM `usuarios` WHERE sexo != ? ORDER BY RAND() LIMIT 1 ");
            $sql->execute(array($_SESSION['sexo']));
            $usuario = $sql->fetch();
            return $usuario;
        }

        public static function pega_crushs($id){
            $crushs = array();
            $sql = MySql::connect()->prepare("SELECT * FROM `likes` WHERE user_from = ? AND action = 1");
            $sql->execute(array($id));
            $gostei = $sql->fetchAll();
            foreach ($gostei as $key => $value) {
                 $sql = MySql::connect()->prepare("SELECT * FROM `likes` WHERE (user_to = ? AND user_from = ?) AND action = 1");
                 $sql->execute(array($id, $value['user_to']));
                 if($sql->rowCount() == 1){
                     $sql = MySql::connect()->prepare("SELECT * FROM `usuarios` WHERE id = ?");
                     $sql->execute(array($value['user_to']));
                     $crushs[] = $sql->fetch();
                 }
            }
            return $crushs;
        }
    }
?>