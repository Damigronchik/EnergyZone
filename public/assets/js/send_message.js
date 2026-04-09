document.addEventListener("DOMContentLoaded", function() {
    const sendButton = document.getElementById("sendButton");
    const textCloseButton = document.getElementById("textCloseButton");
    const iconCloseButton = document.getElementById("iconCloseButton");
    const messageModalWindow = document.getElementById("modalWindow");
    const modalContent = document.getElementById("modalContent");
    
    function closeModalWindow(modalWindow, contect) {
        contect.classList.add("closing")
        setTimeout(() => {
            modalWindow.style.display = "none";
            contect.classList.remove('closing');
        }, 480);
    }
    
    // sendButton.onclick = function() {
    //     messageModalWindow.style.display = "flex";
    // }
    
    textCloseButton.onclick = () => closeModalWindow(messageModalWindow, modalContent)
    iconCloseButton.onclick = () => closeModalWindow(messageModalWindow, modalContent)
    
    // window.onclick = function(event) {
    //     if (event.target == messageModalWindow) {
    //         closeModalWindow(messageModalWindow, modalContent)
    //     }
    // }
});