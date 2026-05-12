document.addEventListener('DOMContentLoaded', function() {
    trainingSelect = document.getElementById('trainingSelect')
    trainerSelect = document.getElementById('trainerSelect')
    
    trainingSelect.addEventListener('change', function() {
        const selectedProgram = this.value;
        trainerSelect.innerHTML = '';
        
        if (selectedProgram && trainersData[selectedProgram]) {
            trainersData[selectedProgram].forEach(trainer => {
                const option = document.createElement('option');
                option.value = trainer.id;
                option.textContent = trainer.name;
                option.classList.add('text-black')
                trainerSelect.appendChild(option);
            });
        }
    });
});
