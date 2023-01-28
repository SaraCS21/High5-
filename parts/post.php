<?php
    $errors = require "./static/constant/errors.php";

    use Controllers\Post;
    use Controllers\Likes;
    use Controllers\Coment;
    use Controllers\Person;
    use Config\Validate;

    // En caso de no tener una sesión iniciada...
    if ($_SESSION["idUser"] === 0 || !isset($_SESSION["idUser"])){
        Header("Location: ./index.php?url=login");
    }

    // En caso de tener la cuenta bloqueada...
    $email = Person::selectEmailPerson()[0][0];
    if (Person::selectBlockPerson($email) === "block"){
        Header("Location: ./index.php?url=login");
    }

    $continueEdit = true;
    $continueEditComent = true;

    $id = isset($_REQUEST["button"]) ? $_REQUEST["button"] : $_REQUEST["idPost"];
    $post = Post::selectPost($id);

    // Incrementamos las visitas del post cada vez que entramos en este
    Post::incrementViews($id);

    // Obtenemos todos los likes que tiene el post en el que nos encontremos
    $likes = (Likes::getLikesPost($id) !== []) ? Likes::getLikesPost($id) : [];
    $arrayLikes = [];

    foreach ($likes as $like) {
        foreach ($like as $key => $value) {
            array_push($arrayLikes, $value);
        }
    }

    // En caso de que queramos enviar un comentario...
    if(isset($_REQUEST["send"])){
        // Las claves de nuestro formulario
        $keysInsert = ["idPost", "content", "url"];

        // Validamos los datos...
        if (Validate::validate("send", $keysInsert) === ""){
            // Creamos el nuevo comentario
            Coment::createComent();
        } 

    // En caso de que queramos eliminar un post...
    } else if (isset($_REQUEST["delete"])){
        Post::deletePost();
        Header("Location: ./index.php?url=landing");


    } else if (isset($_REQUEST["edit"])){
        // Validamos los datos...
        if (!empty($_REQUEST["content"]) && !empty($_REQUEST["title"]) && !empty($_REQUEST["theme"])){
            Post::updatePost();
            Header("Location: ./index.php?url=post&idPost=$id");

        } else {
            $continueEdit = false;
        }

    // En caso de que queramos editar un comentario...
    } else if (isset($_REQUEST["editComent"])){
        // Validamos los datos...
        if (!empty($_REQUEST["content"])){
            Coment::updateComent();
            Header("Location: ./index.php?url=post&idPost=$id");

        } else {
            $continueEditComent = false;
        }

    // En caso de que queramos eliminar un comentario...
    } else if (isset($_REQUEST["deleteComent"])){
        // Coment::deleteComent();
        // Header("Location: ./index.php?url=post&idPost=$id");

    // En caso de que queramos dar like a un post...
    } else if (isset($_REQUEST["like"])){
        Likes::setLike();
        Header("Location: ./index.php?url=post&idPost=$id");

    // En caso de que queramos dar dislike a un post...
    } else if (isset($_REQUEST["dislike"])){
        Likes::deleteLike();
        Header("Location: ./index.php?url=post&idPost=$id");

    } else if (isset($_REQUEST["goBack"])){
        Header("Location: ./index.php?url=landing");
    }
?>

<section class="p-4 mt-5" id="services">
    <div class="container px-4">
        <div class="row gx-4 justify-content-center">
            <div class="col-lg-8">

                <?php if (!$continueEdit){ ?>
                    <div class="w-100 alert alert-danger" role="alert"><?= $errors["errors"]["postEmpty"] ?></div>
                <?php } ?>
                
                <?php if (!$continueEditComent){ ?>
                    <div class="w-100 alert alert-danger" role="alert"><?= $errors["errors"]["comentEmpty"] ?></div>
                <?php } ?>

                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="position-relative">
                    <button type="submit" name="goBack" class="position-absolute border-0 bg-white" style="right:55rem;top:0.5rem;">
                        <i class='bx bx-chevron-left fs-2'></i>
                    </button>

                    <?php 
                        // En caso de que algún post coincida con el "id"...
                        if ($post) {
                            // Obtenemos el id del usuario que ha escrito el post
                            $idUser = $post[0]["idUser"];
                            // Seleccionamos el nombre de ese usuario
                            $name = Person::selectNamePerson($idUser);

                            foreach($post as $row) {
                                // Obtenemos el "id" del post en el que nos encontramos
                                $idPost = $row["id"];
                    ?>
                        <div class="d-flex flex-column pb-4 border-bottom border-1">
                            <h2 class="mb-5"><?= $row["title"] ?></h2>

                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="fw-bold fs-5 text-primary"><?= $name[0][0] ?></p>
                                </div>
                                
                                <div>
                                    <p><?= $row["publicationDate"] ?></p>
                                </div>
                            </div>

                            <div>
                                <p><?= $row["content"] ?></p>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-3">
                                <div>
                                    <span class="badge rounded-pill text-bg-secondary p-2"><?= $row["theme"] ?></span>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <div>

                                        <!-- Si el usuario con sesión activa es un administrador o el creador del post podremos eliminar -->
                                        <?php if (Person::selectTypePerson() === "admin" || $_SESSION["idUser"] === $row["idUser"]){ ?>
                                
                                        <button class="border-0 bg-white" type="submit" name="delete" value="<?= $row["id"] ?>">
                                            
                                        <button type="button" class="border-0 bg-white" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class='bx bxs-trash text-danger fs-4'></i>
                                        </button>

                                        <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Seguro de que quieres eliminar este post?</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Este paso es irreversible.
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" name="delete" value="<?= $row["id"] ?>" class="btn btn-danger">Eliminar post</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Si el usuario con sesión activa es el creador del post podremos editar -->
                                        <?php } if ($_SESSION["idUser"] === $row["idUser"]) { ?>

                                        <!-- <button class="border-0 bg-white" type="submit" name="edit" value="<?= $row["id"] ?>"> -->
                                            
                                        <button type="button" class="border-0 bg-white" data-bs-toggle="modal" data-bs-target="#editModal">
                                            <i class='bx bxs-edit-alt text-warning fs-4'></i>
                                        </button>

                                        <div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Post</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">

                                                        <div class="form-floating mb-4">
                                                            <input type="text" name="title" id="floatingTitle" class="form-control form-control-lg" value="<?= $row["title"] ?>" maxlength="40"/>
                                                            <label class="form-label" for="floatingTitle">Título</label>
                                                        </div>

                                                        <div class="form-floating mb-4">
                                                            <textarea type="text" name="content" id="floatingContent" class="form-control form-control-lg" maxlength="500"><?= $row["content"] ?></textarea>
                                                            <label class="form-label" for="floatingContent">Contenido</label>
                                                        </div>

                                                        <select name="theme" id="floatingType" class="form-control form-select form-control-lg">
                                                            <option value="Ocio" selected>Ocio</option>
                                                            <option value="Videojuegos">Videojuegos</option>
                                                            <option value="Eventos">Eventos</option>
                                                            <option value="Vida diaria">Vida diaria</option>
                                                            <option value="Familia">Familia</option>
                                                            <option value="Viajes">Viajes</option>
                                                        </select>

                                                        <input type="hidden" name="publicationDate" value="<?= $row["publicationDate"] ?>">
                                                        <input type="hidden" name="idUser" value="<?= $row["idUser"] ?>">
                                                        <!-- Url a la que iremos cuando se recargue la página -->
                                                        <input type="hidden" name="url" value="post">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                        <button type="submit" name="edit" value="<?= $row["id"] ?>" class="btn btn-warning">Editar post</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>

                                        <?php if (in_array($_SESSION["idUser"], $arrayLikes)){ ?>

                                        <button type="submit" name="dislike" class="border-0 bg-white">
                                            <i class='bx bxs-like text-primary fs-4'></i>
                                        </button>

                                        <?php } else { ?>

                                        <button type="submit" name="like" class="border-0 bg-white">
                                            <i class='bx bx-like text-primary fs-4'></i>
                                        </button>

                                        <?php } ?>

                                    </div>
                                </div>

                                <!-- Url a la que iremos cuando se recargue la página -->
                                <input type="hidden" name="url" value="post">
                                <input type="hidden" name="idPost" value="<?= $idPost ?>">                                
                            </div>
                        </div>
                    <?php }} ?>
                </div>
                </form>

                <div class="col-lg-8 mt-3">
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="mb-3 d-flex justify-content-between">
                            <input type="hidden" name="idPost" value="<?= $idPost ?>">
                            <!-- Url a la que iremos cuando se recargue la página -->
                            <input type="hidden" name="url" value="post">
                            <input type="text" name="content" class="w-75 form-control" id="exampleContent" placeholder="Escribe un comentario..." maxlength="200">
                            <input type="submit" name="send" class="btn btn-primary" style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Enviar">
                        </div>
                    </form>
                </div>

            <div class="row gx-4 justify-content-center mt-4">
                
                <?php
                    $coment = Coment::selectComentPost($idPost);
                    // En caso de encontrar comentarios...
                    if ($coment){
                ?>

                <div class="col-lg-8 bg-light p-3 rounded">
                    <h4>Comentarios</h4>
                    <hr>

                    <?php
                        foreach($coment as $value){
                            // Obtenemos el "id" del usuario que ha comentado
                            $idUserComent = $value["idUser"];
                            $namePost = Person::selectNamePerson($idUserComent);
                    ?>

                    <div class="p-3">

                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="fw-bold fs-5 text-primary"><?= $namePost[0][0] ?></p>
                            </div>
                            
                            <div>
                                <p><?= $value["publicationDate"] ?></p>
                            </div>
                        </div>

                        <div>
                            <p><?= $value["content"] ?></p>
                        </div>

                        <div class="w-100 d-flex justify-content-end">
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                <!-- Si el usuario con sesión activa es el creador del post podremos eliminar -->
                                <?php if ($_SESSION["idUser"] === $value["idUser"]){ ?>
                                    
                                <button class="border-0 bg-light" type="submit" name="delete" value="<?= $value["id"] ?>">
                                    
                                <button type="button" class="border-0 bg-light" data-bs-toggle="modal" data-bs-target="#deleteComentModal<?= $value["id"] ?>">
                                    <i class='bx bxs-trash text-danger fs-4'></i>
                                </button>

                                <div class="modal fade" id="deleteComentModal<?= $value["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Seguro de que quieres eliminar este comentario?</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Este paso es irreversible.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" name="deleteComent" value="<?= $value["id"] ?>" class="btn btn-danger">Eliminar comentario</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Si el usuario con sesión activa es el creador del post podremos editar -->
                                <?php } if ($_SESSION["idUser"] === $value["idUser"]) { ?>

                                <button class="border-0 bg-light" type="submit" name="edit" value="<?= $value["id"] ?>">
                                    
                                <button type="button" class="border-0 bg-light" data-bs-toggle="modal" data-bs-target="#editComentModal<?= $value["id"] ?>">
                                    <i class='bx bxs-edit-alt text-warning fs-4'></i>
                                </button>

                                <div class="modal fade" id="editComentModal<?= $value["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">Editar Comentario</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">

                                                <div class="form-floating mb-4">
                                                    <textarea type="text" name="content" id="floatingContent" class="form-control form-control-lg" maxlength="500"><?= $value["content"] ?></textarea>
                                                    <label class="form-label" for="floatingContent">Contenido</label>
                                                </div>

                                                <input type="hidden" name="publicationDate" value="<?= $value["publicationDate"] ?>">
                                                <input type="hidden" name="idUser" value="<?= $value["idUser"] ?>">
                                                <input type="hidden" name="idPost" value="<?= $value["idPost"] ?>">
                                                <input type="hidden" name="id" value="<?= $value["id"] ?>">
                                                <!-- Url a la que iremos cuando se recargue la página -->
                                                <input type="hidden" name="url" value="post">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" name="editComent" value="<?= $value["id"] ?>" class="btn btn-warning">Editar post</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <?php }} ?>
                </div>
            </div>
        </div>
    </div>
</section>