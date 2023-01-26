<?php
    use Controllers\Person;
?>

<section>
    <div class="container-fluid h-custom d-flex flex-wrap justify-content-center" style="margin-top:8rem;">

    <?php

        // En caso de no tener una sesión iniciada...
        if ($_SESSION["idUser"] === 0){
            Header("Location: ./index.php?url=login");
        }

        $userValues = Person::selectPerson($_SESSION["idUser"]);

        // En caso de querer cerrar sesión...
        if (isset($_REQUEST["logOut"])){
            // Destruimos la sesión
            session_destroy();
            // Nos movemos de página
            Header("Location: ./index.php");

        // En caso de querer actualizar datos...
        } else if(isset($_REQUEST["update"])){

            // Las claves de nuestro formulario
            $keysInsert = ["name", "surname", "email", "password", "confirmPassword", "age", "url", "type"];
            $validate = validate("update", $keysInsert);

            // Validamos los datos...
            if ($validate === "" && $_REQUEST["password"] !== "" && $_REQUEST["confirmPassword"] !== ""){
                // Actualizamos los datos del usuario
                Person::updatePerson();
    ?>

    <div class="w-50 alert alert-success" role="alert">Los datos se han actualizado correctamente</div>

    <!-- En caso de que algo falle... -->
    <?php } else { ?>

    <div class="w-50 alert alert-danger" role="alert"><?= $validate ?></div>

    <?php
            }

        // En caso de querer eliminar al usuario...
        } else if (isset($_REQUEST["delete"])){
            // Eliminamos al usuario
            Person::deletePerson();
            // Destruimos la sesión
            session_destroy();
            // Nos movemos de página
            Header("Location: ./index.php");
        }
    ?>

        <div class="w-100 row d-flex justify-content-center align-items-center">
            <div class="col-md-8 col-lg-6 col-xl-4">
                <h1 class="text-center mb-5">Bienvenid@ <?= $userValues[0]["name"] ?></h1>
                <form method="post" action="<?= $_SERVER['PHP_SELF'] ?>">
                    <div class="form-floating mb-4">
                        <input type="text" name="name" id="floatingName" class="form-control form-control-lg" maxlength="30" value="<?= $userValues[0]['name'] ?>"/>
                        <label class="form-label" for="floatingName">Nombre</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" name="surname" id="floatingSurname" class="form-control form-control-lg" maxlength="50" value="<?= $userValues[0]['surname'] ?>"/>
                        <label class="form-label" for="floatingSurname">Apellidos</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="email" id="floatingEmail" class="form-control form-control-lg" maxlength="50" value="<?= $userValues[0]['email'] ?>" disabled/>
                        <input type="hidden" name="email" value="<?= $userValues[0]['email'] ?>"/>
                        <label class="form-label" for="floatingEmail">Correo electrónico</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="inputShowPassword" class="form-control form-control-lg" maxlength="16"/>

                        <button id="showPassword" type="button" class="bg-white border-0 position-absolute fs-4 text-secondary" style="top:18%; left:92%">
                            <i class='bx bx-show-alt'></i>
                        </button>

                        <label class="form-label" for="inputShowPassword">Contraseña</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="confirmPassword" id="inputShowConfirmPassword" class="form-control form-control-lg" maxlength="16"/>

                        <button id="showConfirmPassword" type="button" class="bg-white border-0 position-absolute fs-4 text-secondary" style="top:18%; left:92%">
                            <i class='bx bx-show-alt'></i>
                        </button>

                        <label class="form-label" for="inputShowConfirmPassword">Repite la Contraseña</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="integer" name="age" id="floatingAge" class="form-control form-control-lg" value="10" min="10" max="120" value="<?= $userValues[0]['age'] ?>"/>
                        <label class="form-label" for="floatingAge">Edad</label>
                    </div>

                    <!-- Url a la que iremos cuando se recargue la página -->
                    <input type="hidden" name="url" value="userConfig">
                    <input type="hidden" name="type" value="<?= $userValues[0]['type'] ?>">

                    <div class="text-center text-lg-start mt-4 pt-2 d-flex justify-content-between">                        
                        <input type="submit" name="update" class="btn btn-primary" style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Actualizar datos">
                        <input type="submit" name="logOut" class="btn btn-danger" style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Cerrar sesión">
                    </div>
                    
                    <div class="text-center text-lg-start mt-4 pt-2 d-flex justify-content-center">
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Eliminar cuenta</button>
                    </div>

                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">¿Seguro de que quieres eliminar tu cuenta?</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Este paso es irreversible, perderás todos tus datos.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <button type="submit" name="delete" class="btn btn-danger">Eliminar cuenta</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>