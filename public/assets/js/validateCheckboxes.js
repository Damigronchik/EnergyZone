function validateCheckboxes(event) {
    const clickedButton = event.submitter;
    if (clickedButton.value === 'delete') {
        return true;
    }

    const form = clickedButton.form;
    const checkboxes = form.querySelectorAll('.mr-1'); //поменя потом тут класс на правильный
    let checked = false;
    
    for (let checkbox of checkboxes) {
        if (checkbox.checked) {
            checked = true;
            break;
        }
    }
    
    if (!checked) {
        form.querySelector('.checkbox-error').style.display = 'block';
        return false;
    }
    
    return true;
}