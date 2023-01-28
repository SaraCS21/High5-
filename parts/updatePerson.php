<div class="w-100 row d-flex justify-content-center align-items-center mt-5">
    <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">

        <?php

            use Controllers\Person;
            use Config\Debug;
            
            $errors = require "./static/constant/errors.php";

            if (Person::selectTypePerson() !== "admin"){
                Header("Location: ./index.php?url=landing");
            }

            $values = Person::selectPerson($_REQUEST["idUser"])[0];
            $idUser = $_REQUEST["idUser"];

            if (isset($_REQUEST["updatePerson"])){
                if (!empty($_REQUEST["name"]) && !empty($_REQUEST["surname"]) && !empty($_REQUEST["age"])){
                    // Editamos al usuario
                    Person::updatePerson();
                    Header("Location: ./index.php?url=updatePerson&idUser=$idUser");
                } else {

        ?>

        <div class="w-100 alert alert-danger" role="alert"><?= $errors["errors"]["empty"] ?></div>

        <?php
                }

            } else if (isset($_REQUEST["goBack"])){
                    Header("Location: ./index.php?url=adminPanel&userInfo=");
            }
        ?>

        <form method="post" action=<?= "index.php?url=updatePerson&idUser=$idUser" ?>>
            <h2 class="text-center mt-5 mb-4">Actualizar datos de <?= $values["name"] ?></h2>
            <div class="form-floating mb-4">
                <input type="text" name="name" id="floatingName" class="form-control form-control-lg" value="<?= $values["name"] ?>" maxlength="30"/>
                <label class="form-label" for="floatingName">Nombre</label>
            </div>

            <div class="form-floating mb-4">
                <input type="text" name="surname" id="floatingSurname" class="form-control form-control-lg" value="<?= $values["surname"] ?>" maxlength="50"/>
                <label class="form-label" for="floatingSurname">Apellidos</label>
            </div>

            <div class="form-floating mb-4">
                <input type="number" name="age" id="floatingAge" class="form-control form-control-lg" value="<?= $values["age"] ?>" value="10" min="10" max="120"/>
                <label class="form-label" for="floatingAge">Edad</label>
            </div>

            <div class="form-floating mb-4">
                <input type="email" id="floatingEmail" class="form-control form-control-lg" value="<?= $values["email"] ?>" maxlength="50" disabled/>
                <label class="form-label" for="floatingEmail">Correo electrónico</label>
            </div>

            <div class="mb-4">
                <?php if ($values["type"] === "user") { ?>

                <select name="type" id="floatingType" class="form-control form-select form-control-lg">
                    <option value="user" selected>User</option>
                    <option value="admin">Admin</option>
                </select>

                <?php } else { ?>

                <select name="type" id="floatingType" class="form-control form-select form-control-lg">
                    <option value="user">User</option>
                    <option value="admin" selected>Admin</option>
                </select>

                <?php } ?>
            </div>

            <div class="mb-4">
                <?php if ($values["block"] === "block") { ?>

                <select name="block" id="floatingBlock" class="form-control form-select form-control-lg">
                    <option value="block" selected>Bloqueado</option>
                    <option value="unblock">Desbloqueado</option>
                </select>

                <?php } else { ?>

                <select name="block" id="floatingBlock" class="form-control form-select form-control-lg">
                    <option value="block">Bloqueado</option>
                    <option value="unblock" selected>Desbloqueado</option>
                </select>

                <?php } ?>
            </div>
            
            <input type="hidden" name="password" value="<?= $values["password"] ?>">
            <input type="hidden" name="email" value="<?= $values["email"] ?>">
            <input type="hidden" name="editUser" value="<?= $values["id"] ?>">

            <div class="text-center text-lg-start mt-4 pt-2 d-flex justify-content-between">                        
                <input type="submit" name="goBack" class="btn btn-primary" style="padding-left: 1.5rem; padding-right: 1.5rem;" value="Volver atrás"/>
                <input type="submit" name="updatePerson" class="btn btn-warning" style="padding-left: 1.5rem; padding-right: 1.5rem;" value="Actualizar datos"/>
            </div>
        </form>
    </div>
</div>