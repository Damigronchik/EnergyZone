document.addEventListener("DOMContentLoaded", function() {
    const modalWindow = document.getElementById("modalWindow");
    const modalContent = document.getElementById("modalContent");
    const sureDelete = document.getElementById("sureDelete");

    const deleteWindow = document.getElementById("deleteWindow");
    const deleteContent = document.getElementById("deleteContent");
    const deleteButton = document.getElementById("deleteButton");
    const cancelButton = document.getElementById("cancelButton");
    const closeIconButton = document.getElementById("closeIconButton");

    function closeModalWindow(modalWindow, contect) {
        contect.classList.add("closing")
        setTimeout(() => {
            modalWindow.style.display = "none";
            contect.classList.remove("closing");
        }, 480);
    }
    
    sureDelete?.addEventListener('click', function() {
        closeModalWindow(modalWindow, modalContent);
        deleteWindow.style.display = 'flex';
    })

    deleteButton?.addEventListener('click', () => deleteWindow.style.display = 'flex')
    cancelButton?.addEventListener('click', () => closeModalWindow(deleteWindow, deleteContent))
    closeIconButton?.addEventListener('click', () => closeModalWindow(deleteWindow, deleteContent))
});