document.addEventListener("DOMContentLoaded", function() {
    const trainingModalWindow = document.getElementById("modalWindow");
    const trainingModalContent = document.getElementById("modalContent");
    const goBackButton = document.getElementById("goBackButton");
    const iconCloseButton = document.getElementById("iconCloseButton");

    const successWindow = document.getElementById("successWindow");
    const successContent = document.getElementById("successContent");
    const closeSuccess = document.getElementById("closeSuccess");
    const closeSuccessIcon = document.getElementById("closeSuccessIcon");
    const goHomepage = document.getElementById("goHomepage");
    const goAccount = document.getElementById("goAccount");
    const goSchedule = document.getElementById("goSchedule");

    const failWindow = document.getElementById("failWindow");
    const failContent = document.getElementById("failContent");
    const closeFail = document.getElementById("closeFail");
    const closeFailIcon = document.getElementById("closeFailIcon");

    const addButton = document.getElementById("addButton");
    const editPasswordButton = document.getElementById("editPasswordButton");
    const editPassword = document.getElementById("editPassword");

    function closeModalWindow(modalWindow, contect) {
        contect.classList.add("closing")
        setTimeout(() => {
            modalWindow.style.display = "none";
            contect.classList.remove("closing");
        }, 480);
    }
        
    goBackButton?.addEventListener('click', () => closeModalWindow(trainingModalWindow, trainingModalContent))
    iconCloseButton?.addEventListener('click', () => closeModalWindow(trainingModalWindow, trainingModalContent))

    closeSuccess?.addEventListener('click', () => closeModalWindow(successWindow, successContent))
    closeSuccessIcon?.addEventListener('click', () => closeModalWindow(successWindow, successContent))
    goHomepage?.addEventListener('click', function() { window.location.href = "index.php" })
    goAccount?.addEventListener('click', function() { window.location.href = "index.php?page=account" })
    goSchedule?.addEventListener('click', () => closeModalWindow(successWindow, successContent))

    closeFail?.addEventListener('click', () => closeModalWindow(failWindow, failContent))
    closeFailIcon?.addEventListener('click', () => closeModalWindow(failWindow, failContent))

    addButton?.addEventListener('click', () => trainingModalWindow.style.display = 'flex')
    editPasswordButton?.addEventListener('click', () => editPassword.style.display = 'flex')

    // Закрытие по клику вне модального окна
    window.addEventListener('click', function(e) {
        if (e.target === modalWindow) {
            closeModal(modalWindow);
        }
        if (e.target === successWindow) {
            closeModal(successWindow);
        }
        if (e.target === failWindow) {
            closeModal(failWindow);
        }
    });
});