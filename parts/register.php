<section>
    <div class="container-fluid h-custom d-flex flex-wrap justify-content-center mt-5">

        <?php
            // En caso de darle al botón de registrarnos...
            if(isset($_REQUEST["register"])){
                $result = [
                    "error" => false,
                    "mensaje" => "El usuario " . $_REQUEST["name"] . "ha sido agregado con éxito"
                ];

                // Las claves de nuestro formulario
                $keysInsert = ["name", "surname", "age", "email", "password", "confirmPassword", "url"];
                $validate = validate("register", $keysInsert);

                // Validamos los datos...
                if ($validate === ""){
                    // Creamos el nuevo usuario
                    Person::createPerson();
                    // Nos movemos a la página principal
                    Header("Location: ./index.php?url=login");
        ?>

        <div class="w-50 alert alert-success" role="alert">Los datos se han insertado correctamente</div>

        <!-- En caso de que algo falle... -->
        <?php } else { ?>

        <div class="w-50 alert alert-danger" role="alert"><?= $validate ?></div>

        <?php
                }

            // En caso de querer loguearnos...
            } else if (isset($_REQUEST["login"])){
                // Nos movemos a la página de inicio de sesión
                Header("Location: ./index.php?url=login");
            }
        ?>

        <div class="w-100 row d-flex justify-content-center align-items-center mt-5">
            <div class="col-md-9 col-lg-6 col-xl-5">
                <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
                class="img-fluid" alt="Sample image">
            </div>

            <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
                <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <div class="form-floating mb-4">
                        <input type="text" name="name" id="floatingName" class="form-control form-control-lg" maxlength="30"/>
                        <label class="form-label" for="floatingName">Nombre</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="text" name="surname" id="floatingSurname" class="form-control form-control-lg" maxlength="50"/>
                        <label class="form-label" for="floatingSurname">Apellidos</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="number" name="age" id="floatingAge" class="form-control form-control-lg" value="10" min="10" max="120"/>
                        <label class="form-label" for="floatingAge">Edad</label>
                    </div>

                    <div class="form-floating mb-4">
                        <input type="email" name="email" id="floatingEmail" class="form-control form-control-lg" maxlength="50"/>
                        <label class="form-label" for="floatingEmail">Correo electrónico</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="inputShowPassword" class="form-control form-control-lg" maxlength="16">

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

                    <!-- Url a la que iremos cuando se recargue la página -->
                    <input type="hidden" name="url" value="register">

                    <div class="text-center text-lg-start mt-4 pt-2">
                        <input type="submit" name="register" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Regístrate">
                        <p class="small fw-bold mt-2 pt-1 mb-0 d-flex align-items-center">
                            ¿Ya tienes una cuenta? 
                            <button type="submit" name="login" class="btn btn-link btn-sm">Inicia sesión</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>