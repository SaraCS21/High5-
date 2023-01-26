<?php
    use Controllers\Person;
    use Config\Validate;
?>

<section>
    <div class="container-fluid h-custom d-flex flex-wrap justify-content-center" style="margin-top:8rem;">

    <?php
        $errors = require "./static/constant/errors.php";

        // En caso de darle al botón de loguearnos...
        if(isset($_REQUEST["login"])){

            // Las claves de nuestro formulario
            $keysInsert = ["email", "password", "url"];
            $validate = Validate::validate("login", $keysInsert);

            // Validamos los datos...
            if ($validate === ""){
                // En caso de que algo falle...
                if (Person::comprobePerson() !== ""){  
    ?>

<div class="w-50 alert alert-danger" role="alert"><?= Person::comprobePerson() ?></div>

    <?php
                } else {
                    // Ponemos el "id" dentro de la sesión
                    $_SESSION["idUser"] = (Person::selectIdPerson()) ? Person::selectIdPerson()[0][0] : 0;
                    // Nos movemos a la página principal
                    Header("Location: ./index.php?url=landing");
                }

            } else if ($validate !== "") {
                $_SESSION["idUser"] = 0;
    ?>

<div class="w-50 alert alert-danger" role="alert"><?= $validate ?></div>

    <?php
            }

        // En caso de querer registrarnos...
        } else if (isset($_REQUEST["register"])){
            // Nos movemos a la página de registro
            Header("Location: ./index.php?url=register");
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
                        <input type="email" name="email" id="floatingEmail" class="form-control form-control-lg" maxlength="50"/>
                        <label class="form-label" for="floatingEmail">Correo electrónico</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" name="password" id="inputShowPassword" class="form-control form-control-lg rounded-start position-relative" maxlength="70"/>
                        
                        <button id="showPassword" type="button" class="bg-white border-0 position-absolute fs-4 text-secondary" style="top:18%; left:92%">
                            <i class='bx bx-show-alt'></i>
                        </button>

                        <label class="form-label" for="inputShowPassword">Contraseña</label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="form-check mb-0">
                            <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3" />
                            <label class="form-check-label" for="form2Example3">Recuérdame</label>
                        </div>
                        <a href="#!" class="text-body">¿Has olvidado la contraseña?</a>
                    </div>

                    <!-- Url a la que iremos cuando se recargue la página -->
                    <input type="hidden" name="url" value="login">

                    <div class="text-center text-lg-start mt-4 pt-2">                        
                        <input type="submit" name="login" class="btn btn-primary btn-lg" style="padding-left: 2.5rem; padding-right: 2.5rem;" value="Inicia Sesión">
                        <p class="small fw-bold mt-2 pt-1 mb-0 d-flex align-items-center">
                            ¿Aún no tienes una cuenta? 
                            <button type="submit" name="register" class="btn btn-link btn-sm">Regístrate</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>