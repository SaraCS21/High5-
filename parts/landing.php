<?php

    // En caso de no tener una sesión iniciada...
    if ($_SESSION["idUser"] === 0){
        Header("Location: ./index.php?url=login");
    }

    echo $_SESSION["idUser"];

    try {
        $connection = connect();

        $querySQL = "SELECT * FROM post";
        $sentence = $connection->prepare($querySQL);
        $sentence->execute();

        $posts = $sentence->fetchAll();
        $continue = ($posts && $sentence->rowCount()>0) ? true : false;

    } catch(PDOException $error) {
        $error = $error->getMessage();
    }

    if (isset($_REQUEST["newTheme"])){
        Header("Location: ./index.php?url=newTheme");

    } 

?>

<section id="about" class="p-4">
    <div class="container px-4">
        <div class="row gx-4 justify-content-center">
            <div class="col-lg-8">
                <h2>Sobre nosotros</h2>
                <p class="lead">Bienvenido a high5! Aquí podrás encontrar un montón de espacios donde poder hablar con otras personas sobre lo que quieras.</p>
                <ul>
                    <li>Podrás encontrar a miles de personas con tus mismos gustos.</li>
                    <li>Infórmate sobre aquello que más te apasione.</li>
                    <li>Debate y pon en común ideas con el resto de la comunidad.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="bg-light p-4" id="services">
    <div class="container px-4">
        <div class="row gx-4 justify-content-center">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between mt-3">                
                    <h2>Temas</h2>
                    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <!-- Url a la que iremos cuando se recargue la página -->
                        <input type="hidden" name="url" value="newTheme">
                        <button type="submit" name="newTheme" class="btn btn-secondary">Crea un nuevo debate</button>
                    </form>
                </div>
                
                <hr><br>

                <!-- Mostramos todos los posts de nuestra Base de Datos -->
                <?php 
                    if ($continue) {
                        foreach($posts as $row) {
                ?>

                    <div class="d-flex flex-column pb-4 border-bottom border-1">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h5><?= $row["title"] ?></h5>
                                <p><?= $row["publicationDate"] ?></p>
                            </div>
                            
                            <div class="fs-4">
                                <i class='bx bx-show me-2'></i>
                                <i class='bx bx-like me-2'></i>
                                <i class='bx bx-chat'></i>
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