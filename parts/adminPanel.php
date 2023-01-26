<?php

    use Controllers\Person;
    use Controllers\Post;

    $typeUser = Person::selectTypePerson();

    // En caso de que el usuario no sea un administrador...
    if (Person::selectTypePerson() !== "admin"){
        Header("Location: ./index.php?url=landing");
    }

    $posts = false;
    $users = false;
    $continueUpdatePost = false;

    // En caso de que queramos ver la información de los posts...
    if (isset($_REQUEST["postInfo"])){
        $posts = Post::selectAllPost();

        // Todas las claves para los th de la tabla
        $keys = ["Id", "Contenido", "Título", "Tema", "Publicación", "Id Usuario"];
    }
    
    // En caso de que queramos ver la información de los usuarios...
    if (isset($_REQUEST["userInfo"])){
        $users = Person::selectAllPerson();

        // Todas las claves para los th de la tabla
        $keys = ["Id", "Nombre", "Apellido", "Correo", "Edad", "Tipo", "Bloqueado"];
    }

    // En caso de querer eliminar un post...
    if (isset($_REQUEST["deletePost"])){
        Post::deletePost();

    // En caso de querer eliminar un usuario...
    } else if (isset($_REQUEST["deleteUser"])){
        Person::deletePerson();

    // En caso de querer editar un post...
    } else if (isset($_REQUEST["editPost"])){
        $idPost = $_REQUEST["editPost"];
        // Nos movemos a la página de edición del post
        Header("Location: ./index.php?url=updatePost&idPost=$idPost");

    // En caso de querer editar un usuario...
    } else if (isset($_REQUEST["editUser"])){
        $idUser = $_REQUEST["editUser"];
        // Nos movemos a la página de edición del usuario
        Header("Location: ./index.php?url=updatePerson&idUser=$idUser");
    } 
    
    // Si venimos de actualizar a un usuario...
    if (isset($_REQUEST["updatePerson"])){

        // Validamos los datos...
        if (!empty($_REQUEST["name"]) && !empty($_REQUEST["surname"]) && !empty($_REQUEST["age"])){
            // Editamos al usuario
            Person::updatePerson();
            Header("Location: ./index.php?url=adminPanel");
        }     

    // Si venimos de actualizar un post...
    } else if (isset($_REQUEST["updatePost"])){
        // Validamos los datos...
        if (!empty($_REQUEST["content"]) && !empty($_REQUEST["title"]) && !empty($_REQUEST["theme"])){
            // Editamos el post
            Post::updatePost();
            Header("Location: ./index.php?url=adminPanel");
        }         
    }
?> 

<nav class="navbar navbar-expand-lg bg-body-tertiary navbar-light bg-white">
    <div class="container-fluid">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="container-fluid d-flex">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <button type="submit" name="userInfo" class="navbar-brand ms-3 mt-2 mt-lg-0 border-0 bg-light">
                    Usuarios
                </button>

                <button type="submit" name="postInfo" class="navbar-brand ms-3 mt-2 mt-lg-0 border-0 bg-light">
                    Posts
                </button>
            </div>

            <!-- Url a la que iremos cuando se recargue la página -->
            <input type="hidden" name="url" value="adminPanel">
        </form>
    </div>
</nav>

<!-- En caso de querer ver los datos de los posts... -->
<?php if ($posts){ ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="w-100 d-flex flex-wrap justify-content-center mt-5">
            <h2 class="w-100 text-center mb-5">Tabla de Posts</h2>
            <table class="table table-bordered w-75">
                <thead class="table-secondary text-center">
                    <tr>
                        <?php foreach($keys as $key){ ?>
                            <th scope="col"><?= $key ?></th>
                        <?php } ?>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($posts as $post){ ?>
                        <tr>
                            <th class="text-center" scope="row"><?= $post["id"] ?></th>
                            <td class="w-50"><?= $post["content"] ?></td>
                            <td><?= $post["title"] ?></td>
                            <td class="text-center"><?= $post["theme"] ?></td>
                            <td class="text-center"><?= $post["publicationDate"] ?></td>
                            <td class="text-center"><?= $post["idUser"] ?></td>

                            <!-- Botones con las diferentes acciones que puede hacer el administrador -->
                            <td class="d-flex justify-content-center align-items-center">
                                    
                                <!-- Modal de eliminación -->
                                <button class="border-0 bg-light" type="submit" name="delete" value="<?= $post["id"] ?>">
                                    
                                <button type="button" name="delete" value="<?= $post["id"] ?>" class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#deletePostModal<?= $post["id"] ?>">
                                    <i class='bx bxs-trash'></i>
                                </button>

                                <div class="modal fade" id="deletePostModal<?= $post["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
                                                <button type="submit" name="deletePost" value="<?= $post["id"] ?>" class="btn btn-danger">Eliminar Post</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón de edición -->
                                <button type="submit" name="editPost" value="<?= $post["id"] ?>" class="btn btn-warning">
                                    <i class='bx bxs-edit-alt'></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <!-- Url a la que iremos cuando se recargue la página -->
        <input type="hidden" name="url" value="adminPanel">

    </form>
                    
<!-- En caso de querer ver los datos de los usuarios... -->
<?php } else if ($users){ ?>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="w-100 d-flex flex-wrap justify-content-center mt-5">
            <h2 class="w-100 text-center mb-5">Tabla de Usuarios</h2>
            <table class="table table-bordered w-50">
                <thead class="table-secondary text-center">
                    <tr>
                        <?php foreach($keys as $key){ ?>
                            <th scope="col"><?= $key ?></th>
                        <?php } ?>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user){ ?>
                        <tr>
                            <th class="text-center" scope="row"><?= $user["id"] ?></th>
                            <td class="text-center"><?= $user["name"] ?></td>
                            <td class="text-center"><?= $user["surname"] ?></td>
                            <td class="text-center"><?= $user["email"] ?></td>
                            <td class="text-center"><?= $user["age"] ?></td>
                            <td class="text-center"><?= $user["type"] ?></td>
                            <td class="text-center"><?= $user["block"] ?></td>

                            <!-- Botones con las diferentes acciones que puede hacer el administrador -->
                            <td class="d-flex justify-content-center align-items-center">

                                <!-- Modal de eliminación -->
                                <button class="border-0 bg-light" type="submit" name="delete" value="<?= $user["id"] ?>">
                                        
                                <button type="button" name="delete" value="<?= $user["id"] ?>" class="btn btn-danger me-3" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?= $user["id"] ?>">
                                    <i class='bx bxs-trash'></i>
                                </button>

                                <div class="modal fade" id="deleteUserModal<?= $user["id"] ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Seguro de que quieres eliminar a este usuario?</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Este paso es irreversible.
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                                <button type="submit" name="deleteUser" value="<?= $user["id"] ?>" class="btn btn-danger">Eliminar Usuario</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Botón de edición -->
                                <button type="submit" name="editUser" value="<?= $user["id"] ?>" class="btn btn-warning">
                                    <i class='bx bxs-edit-alt'></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <!-- Url a la que iremos cuando se recargue la página -->
        <input type="hidden" name="url" value="adminPanel">
    </form>
<?php } ?>