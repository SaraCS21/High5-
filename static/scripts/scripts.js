const show = document.querySelector("#showPassword");
const input = document.querySelector("#inputShowPassword");

show.addEventListener("click", e => {
    const i = show.querySelector("i");

    if (i.className === "bx bx-show-alt"){
        i.classList = "bx bx-low-vision";
        input.type = "text";

    } else {
        i.classList = "bx bx-show-alt";
        input.type = "password";

    }

})

const showConfirm = document.querySelector("#showConfirmPassword");
const inputConfirm = document.querySelector("#inputShowConfirmPassword");

showConfirm.addEventListener("click", e => {
    const i = showConfirm.querySelector("i");

    if (i.className === "bx bx-show-alt"){
        i.classList = "bx bx-low-vision";
        inputConfirm.type = "text";

    } else {
        i.classList = "bx bx-show-alt";
        inputConfirm.type = "password";

    }

})