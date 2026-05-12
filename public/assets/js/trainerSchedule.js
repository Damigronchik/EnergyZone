document.addEventListener('DOMContentLoaded', function() {
    const modalWindow = document.getElementById('modalWindow');
    const modalTitle = document.getElementById('modalTitle');
    const modalPlaces = document.getElementById('modalPlaces');
    const modalPrice = document.getElementById('modalPrice');
    const modalRating = document.getElementById('modalRating');

    const updateMode = document.getElementById('updateMode');
    const createMode = document.getElementById('createMode');
    const modalTrainingId = document.getElementById('modalTrainingId');
    const weekDayInput = document.getElementById('weekDay');
    const startTimeInput = document.getElementById('startTime');
    const trainingSelect = document.getElementById('trainingSelect');
    
    let currentButton = null;
    
    document.querySelectorAll('.active-training').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentButton = this;
            
            const mode = this.dataset.mode;            
            const trainingName = this.dataset.trainingName;
            const peopleAmount = parseInt(this.dataset.peopleAmount);
            const price = parseInt(this.dataset.price);
            const rating = this.dataset.rating;
            const trainingId = this.dataset.trainingId;
            
            if (mode == 'view') {
                modalTitle.textContent = `Занятие "${trainingName}"`;
                modalTitle.setAttribute('data-text', modalTitle.textContent);                
                modalPlaces.textContent = `Количество мест: ${peopleAmount}`;
                modalPrice.textContent = `Стоимость: ${price} руб.`;
                modalRating.textContent = `Рейтинг: ${rating}`;
            }
            else if (mode == 'wishlist') {
                updateMode.style.display = 'flex';
                createMode.style.display = 'none';

                modalTitle.textContent = 'Изменить занятие';
                modalTitle.setAttribute('data-text', modalTitle.textContent);

                modalPlaces.value = peopleAmount;
                modalTrainingId.value = trainingId;
                trainingSelect.value = trainingName;
            }            
            modalWindow.style.display = 'flex';
        });
    });    
    
    document.querySelectorAll('.new-training').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentButton = this;
            
            const weekday = this.dataset.weekday;
            const hour = this.dataset.hour;
            
            updateMode.style.display = 'none';
            createMode.style.display = 'flex';

            modalTitle.textContent = 'Добавить занятие';
            modalTitle.setAttribute('data-text', modalTitle.textContent);
            weekDayInput.value = weekday;
            startTimeInput.value = hour;
            trainingSelect.value = 'none';
                        
            modalWindow.style.display = 'flex';
        });
    }); 
    
    

    const modeLinks = document.querySelectorAll('.schedule__change-button');

    modeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            localStorage.setItem('scrollPosition', window.scrollY);
        });
    });
        
    const savedPosition = localStorage.getItem('scrollPosition');
    if (savedPosition) {
        setTimeout(function() {
            window.scrollTo(0, parseInt(savedPosition));
        }, 50);
    }
});