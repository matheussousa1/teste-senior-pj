<?php
    define("CONT", "app/controller/");
    define("VIEW", "app/view/");
    define("MODEL", "app/model/");
    define("SITE", "/testes/teste_senior_pj/");
    define("AJAX", SITE."app/ajax/");
    
    
    global $url, $controller, $action, $classe;
    
    if(!empty($_GET['url'])){
        
        $url = $_GET['url'];
        $url = explode('/', $url);
        
        if(count($url) == 1){
            $url[] = "inicio";
        }
        
    }else{
        $url = array("inicio", "inicio");
    }
    
    $controller = $url[0];
    $action = $url[1];
    
    if(file_exists(CONT.$controller.".php")){
        
        session_start();
        
        $_SESSION['base'] = SITE;

        include_once (CONT.$controller.".php");
        $classe = new $controller;
        
        //date_default_timezone_set('America/Manaus');
        
        if(method_exists($classe, $action)){
            
            //verificar se session está ativa
            if (!isset($_SESSION['idSession'])) {
                //verificar as sessões somente nos controladores que não tem abaixo
                if(($controller != "login")  && ($controller != "inscrever")){
                    session_destroy();
                    session_unset();
                    
                    header("Location: ".SITE."login");
                }
            }

            $classe->$action();
            
            //verifica se o controller é diferente de login, se for ele verifica se está logado.
            if(($controller != "login")){
                //Validação de Sessão e do nível de acesso
                include_once (CONT.'login.php');
                $verificaLogin = new Login;
                $verificaLogin->verifica();
                $verificaLogin->verificaNivelUsuario($classe->nivel);

            }
            $menu = "";
            $nivelUser = (isset($_SESSION['nivelSession']) ? $_SESSION['nivelSession'] : 0);
            switch ($nivelUser) {
                case '1':
                    // Administrador
                    $menu = "menuAdmin";
                break;
                default:
                    $menu = "menuLogin";
                break;
            }


            if($classe->view != ""){
                $con = condb();
                //incluir páginas
                if ($controller == "login") {
                    include(VIEW.'estrutura/cabecalhoLogin.php');
                }else{
                    $idRef = $_SESSION['idSession'];
                    include(VIEW.'estrutura/cabecalho.php');
                    include(VIEW.'estrutura/'.$menu.'.php');
                }
                
                $pagina = $classe->view;
                
                include_once(VIEW.$pagina.'.php');

                if (($controller == "login")) {
                    include(VIEW.'estrutura/rodapeLogin.php');
                }else{
                    include(VIEW.'estrutura/rodape.php');
                }
                
            }
            
        }else{
            //incluir páginas
            include(VIEW.'estrutura/cabecalho.php');
            include(VIEW.'estrutura/404.php');
        }
        
    }else{
        //incluir páginas
        include(VIEW.'estrutura/cabecalho.php');
        include(VIEW.'estrutura/404.php');
    }
    
?>