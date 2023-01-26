<?php   
    use Controllers\Post;
    use Controllers\Person;
    use Config\Validate;
?>

<section>
    <div class="container-fluid h-custom d-flex flex-wrap justify-content-center" style="margin-top:8rem;">

    <?php

        // En caso de no tener una sesión iniciada...
        if ($_SESSION["idUser"] === 0 || !isset($_SESSION["idUser"])){
            Header("Location: ./index.php?url=login");
        }

        // En caso de tener la cuenta bloqueada...
        $email = Person::selectEmailPerson()[0][0];
        if (Person::selectBlockPerson($email) === "block"){
            Header("Location: ./index.php?url=login");
        }

        if (isset($_REQUEST["goBack"])){
            Header("Location: ./index.php?url=landing");
        }

        if(isset($_REQUEST["createTheme"])){
            $result = [
                "error" => false,
                "mensaje" => "El post ha sido agregado con éxito"
            ];

            // Las claves de nuestro formulario
            $keysInsert = ["title", "theme", "content", "url"];
            $validate = Validate::validate("createTheme", $keysInsert);

            // Validamos los datos...
            if ($validate === ""){
                // Creamos el nuevo post
                Post::createPost();
                // Nos movemos a la página principal
                Header("Location: ./index.php?url=landing");
            
            // En caso de que algo falle...
            } else { 
    ?>

    <div class="w-50 alert alert-danger" role="alert"><?= $validate ?></div>

    <?php }} ?>

        <div class="w-100 row d-flex justify-content-center align-items-center mt-5">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <h2 class="mb-5 text-center">Crea un nuevo espacio del que hablar</h2>
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="position-relative">
                    <button type="submit" name="goBack" class="position-absolute border-0 bg-white" style="right:40rem;bottom:18.5rem;">
                        <i class='bx bx-chevron-left fs-2'></i>
                    </button>
                    
                    <div class="form-floating mb-3">
                        <input type="text" name="title" id="floatingTitle" class="form-control form-control-lg" placeholder="Enter title" maxlength="40"/>
                        <label class="form-label" for="floatingTitle">Título</label>
                    </div>

                    <div class="mb-3">
                        <select name="theme" id="floatingType" class="form-control form-select form-control-lg">
                            <option value="Ocio" selected>Ocio</option>
                            <option value="Videojuegos">Videojuegos</option>
                            <option value="Eventos">Eventos</option>
                            <option value="Vida diaria">Vida diaria</option>
                            <option value="Familia">Familia</option>
                            <option value="Viajes">Viajes</option>
                        </select>
                    </div>

                    <div class="form-floating mb-3">
                        <textarea class="form-control" name="content" placeholder="Leave a comment here" id="floatingContent" maxlength="500"></textarea>
                        <label for="floatingContent">Contenido</label>
                    </div>

                    <!-- Url a la que iremos cuando se recargue la página -->
                    <input type="hidden" name="url" value="newTheme">

                    <input type="submit" name="createTheme" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Crear">
                </form>
            </div>
        </div>
    </div>
</section>