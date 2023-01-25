<?php

    if (selectTypePerson() !== "admin"){
        Header("Location: ./index.php?url=landing");
    }

    $values = selectPost($_REQUEST["idPost"])[0];

?>

<div class="w-100 row d-flex justify-content-center align-items-center mt-5">
    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

        <form method="post" action="index.php?url=adminPanel">
            <h2 class="text-center mt-5 mb-4">Actualizar datos del Post</h2>

            <div class="form-floating mb-4">
                <input type="text" name="title" id="floatingTitle" class="form-control form-control-lg" value="<?= $values["title"] ?>" maxlength="40"/>
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

            <div class="form-floating mb-4">
                <input type="text" name="content" id="floatingContent" class="form-control form-control-lg" value="<?= $values["content"] ?>" maxlength="500"/>
                <label class="form-label" for="floatingContent">Contenido</label>
            </div>

            <input type="hidden" name="publicationDate" value="<?= $values["publicationDate"] ?>">
            <input type="hidden" name="idUser" value="<?= $values["idUser"] ?>">
            <input type="hidden" name="idPost" value="<?= $values["id"] ?>">

            <div class="text-center text-lg-start mt-4 pt-2 d-flex justify-content-center">                        
                <input type="submit" name="updatePost" class="btn btn-warning" style="padding-left: 1.5rem; padding-right: 1.5rem;" value="Actualizar datos"/>
            </div>
        </form>
    </div>
</div>