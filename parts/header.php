<?php
    /** 
     * @author Sara del Pino Cabrera Sánchez - 2ºDAW
     * @copyright 2023 - Sara del Pino Cabrera Sánchez - 2ºDAW
    */

    session_start(); // Iniciamos la sesión
    
    require "./config/functions.php";
    require "./config/create.php";
    require "./controllers/Person.php";
    require "./controllers/Post.php";

    // En caso de que queramos movernos a la página principal...
    if (isset($_REQUEST["landing"])){
        Header("Location: ./index.php?url=landing");

    // En caso de que queramos movernos a la página de configuración del usuario...
    } else if (isset($_REQUEST["userConfig"])){
        Header("Location: ./index.php?url=userConfig");

    // En caso de que queramos movernos al panel del administrador...
    } else if (isset($_REQUEST["adminPanel"])){
        Header("Location: ./index.php?url=adminPanel");

    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="shortcut icon" href="./static/img/favicon.ico" type="image/x-icon">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="stylesheet" href="../static/css/style.css">

    <title>High5!</title>
</head>
<body class="position-relative" style="min-height: 100%;">
    <nav class="navbar navbar-expand-lg bg-body-tertiary navbar-light bg-white">
        <div class="container-fluid">
            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="container-fluid d-flex">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class='bx bx-menu' ></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <button type="submit" name="landing" class="navbar-brand ms-3 mt-2 mt-lg-0 border-0 bg-light">
                        <img src="./static/img/logo.png" height="30" alt="MDB Logo" loading="lazy"/>
                    </button>
                </div>

                <div class="d-flex justify-content-end">

                    <!-- Si el usuario con sesión activa es un administrador podremos ver un icono extra para acceder al panel de administración -->
                    <?php if (Person::selectTypePerson() === "admin"){ ?>

                    <button type="submit" class="d-flex align-items-center border-0 bg-light" name="adminPanel" aria-expanded="false">
                        <i class='bx bxs-cog fs-3 text-dark'></i>                
                    </button>

                    <?php } ?>

                    <button type="submit" class="d-flex align-items-center border-0 bg-light" name="userConfig" aria-expanded="false">
                        <i class='bx bxs-user-circle fs-3 text-dark'></i>                
                    </button>

                </div>

                <!-- Url a la que iremos cuando se recargue la página -->
                <input type="hidden" name="url" value="landing">
            </form>
        </div>
    </nav>