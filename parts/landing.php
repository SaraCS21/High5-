<?php

    use Controllers\Post;
    use Controllers\Likes;
    use Controllers\Coment;
    use Controllers\Person;

    // En caso de no tener una sesión iniciada...
    if ($_SESSION["idUser"] === 0 || !isset($_SESSION["idUser"])){
        Header("Location: ./index.php?url=login");
    }

    // En caso de tener la cuenta bloqueada...
    $email = Person::selectEmailPerson()[0][0];
    if (Person::selectBlockPerson($email) === "block"){
        Header("Location: ./index.php?url=login");
    }

    // En caso de querer crear un nuevo post
    if (isset($_REQUEST["newTheme"])){
        Header("Location: ./index.php?url=newTheme");
    } 

    // En caso de querer filtar los post
    if (isset($_REQUEST["filter"])){
        $posts = Post::filterPost();
        
    } else {
        // Seleccionamos todos los post de nuestra Base de Datos
        $posts = Post::selectAllPost();
    }
?>

<section id="about" class="p-4">
    <div class="container">
        <div class="row height d-flex justify-content-center align-items-center">
            <div class="col-md-6">
                <h2 class="pb-5 text-center fs-1">High5!</h2>
                <div class="form">
                    <i class='bx bx-search'></i>
                    <input type="text" class="form-control form-input" placeholder="Busca lo que quieras...">
                    <span class="left-pan"><i class='bx bx-microphone'></i></span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-light p-4" id="services">
    <div class="container px-4">
        <div class="row gx-4 justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mt-3 pb-3">
                    <select id="filter" class="w-25 form-select form-select-sm" onChange=location.href="./index.php?url=landing&filter="+this.value>
                        <option value="all" selected>Filtrar por...</option>
                        <option value="topViews">Más vistos</option>
                        <option value="lessViews">Menos vistos</option>
                        <option value="topLikes">Con más likes</option>
                        <option value="lessLikes">Con menos likes</option>
                        <option value="topComents">Más comentados</option>
                        <option value="lessComents">Menos comentados</option>
                    </select>
                </div>

                <div class="d-flex justify-content-between mt-3">                
                    <h2>Posts</h2>
                    <form method="post" action="<?= $_SERVER['PHP_SELF']; ?>">
                        <!-- Url a la que iremos cuando se recargue la página -->
                        <input type="hidden" name="url" value="newTheme">
                        <button type="submit" name="newTheme" class="btn btn-secondary">Crea un nuevo post</button>
                    </form>
                </div>
                <hr>

                <!-- Mostramos todos los posts de nuestra Base de Datos -->
                <?php 
                    if ($posts) {
                        foreach($posts as $row) {
                ?>

                    <div class="d-flex flex-column pb-4 border-bottom border-1 mt-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5><?= $row["title"] ?></h5>
                                <p><?= $row["publicationDate"] ?></p>
                            </div>
                            
                            <div class="fs-4">
                                <i class='bx bx-show me-2 position-relative'>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.8rem; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">
                                        <?= Post::getViews($row["id"]) ?>
                                    </span>
                                </i>

                                <i class='bx bx-like me-2 position-relative'>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.8rem; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">
                                        <?= Likes::countLikesPost($row["id"]) ?>
                                    </span>
                                </i>

                                <i class='bx bx-chat position-relative'>
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.8rem; font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif">
                                        <?= Coment::contComents($row["id"]) ?>
                                    </span>
                                </i>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <div>
                                <span class="badge rounded-pill text-bg-secondary p-2"><?= $row["theme"] ?></span>
                            </div>

                            <div>
                                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <!-- Url a la que iremos cuando se recargue la página -->
                                    <input type="hidden" name="url" value="post">
                                    <button type="submit" name="button" value="<?= $row["id"] ?>" class="btn btn-primary">Leer más</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php }} ?>
            </div>
        </div>
    </div>
</section>