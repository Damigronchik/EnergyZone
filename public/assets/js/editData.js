document.addEventListener("DOMContentLoaded", function() {
    const regPassword = document.getElementById("regPassword");
    const regShowPassword = document.getElementById("regShowPassword");
    const regRepeatPassword = document.getElementById("regRepeatPassword");
    const regShowRepeatPassword = document.getElementById("regShowRepeatPassword");
    
    const iconCloseButton = document.getElementById("editPasswordClose");
    const editPassword = document.getElementById("editPassword");

    function showPassword(password, icon) {
        if (password.type == 'password') {
            password.type = 'text';
            icon.src = 'assets/images/showPassword_icon.png';
            icon.style.height = '19px';
            icon.style.top = '52px';
        }
        else {
            password.type = 'password';
            icon.src = 'assets/images/hidePassword_icon.png';
            icon.style.height = '27px';
            icon.style.top = '48px';
        }
    }
    
    regShowPassword.onclick = () => showPassword(regPassword, regShowPassword);
    regShowRepeatPassword.onclick = () => showPassword(regRepeatPassword, regShowRepeatPassword);

    iconCloseButton?.addEventListener('click', () => editPassword.style.display = 'none')
})
