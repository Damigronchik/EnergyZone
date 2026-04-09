const accountButton = document.getElementById("accountButton");

const regButton = document.getElementById("regButton");
const regModalWindow = document.getElementById("regModalWindow");
const regContent = document.getElementById("regContent");
const regChange = document.getElementById("regChange");
const regClose = document.getElementById("regClose");
const regPassword = document.getElementById("regPassword");
const regShowPassword = document.getElementById("regShowPassword");
const regRepeatPassword = document.getElementById("regRepeatPassword");
const regShowRepeatPassword = document.getElementById("regShowRepeatPassword");

const logButton = document.getElementById("logButton");
const logModalWindow = document.getElementById("logModalWindow");
const logContent = document.getElementById("logContent");
const logChange = document.getElementById("logChange");
const logClose = document.getElementById("logClose");
const logPassword = document.getElementById("logPassword");
const logShowPassword = document.getElementById("logShowPassword");

function closeModalWindow(modalWindow, contect) {
    modalWindow.style.display = "none";
    // contect.classList.add("closing")
    // setTimeout(() => {
    //     modalWindow.style.display = "none";
    //     contect.classList.remove('closing');
    // }, 480);
}

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

function changeModalWindow(oldModalWindow, newModalWindow) {
    oldModalWindow.style.display = "none";
    newModalWindow.style.display = "flex";
}

accountButton.onclick = function() {
    logModalWindow.style.display = "flex";
}

regClose.onclick = () => closeModalWindow(regModalWindow, regContent);
logClose.onclick = () => closeModalWindow(logModalWindow, logContent);

regChange.onclick = () => changeModalWindow(regModalWindow, logModalWindow)
logChange.onclick = () => changeModalWindow(logModalWindow, regModalWindow)

logShowPassword.onclick = () => showPassword(logPassword, logShowPassword);
regShowPassword.onclick = () => showPassword(regPassword, regShowPassword);
regShowRepeatPassword.onclick = () => showPassword(regRepeatPassword, regShowRepeatPassword);