document.addEventListener('DOMContentLoaded', function() {
    // Получаем элементы модальных окон
    const modalWindow = document.getElementById('modalWindow');
    
    // Получаем элементы для отображения данных
    const modalTitle = document.getElementById('modalTitle');
    const modalTrainer = document.getElementById('modalTrainer');
    const modalPlaces = document.getElementById('modalPlaces');
    const modalPrice = document.getElementById('modalPrice');
    const modalRating = document.getElementById('modalRating');
    const signupTrainingId = document.getElementById('signupTrainingId');
    const signupWeekday = document.getElementById('signupWeekday');
    const signupHour = document.getElementById('signupHour');
    const signupButton = document.getElementById('signupButton');
    
    // Переменная для хранения текущей кнопки (для обновления данных)
    let currentButton = null;
    
    // Обработчик клика по всем кнопкам тренировок
    document.querySelectorAll('.active-training').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            currentButton = this;
            
            // Получаем данные из data-атрибутов
            const trainingName = this.dataset.trainingName;
            const trainerName = this.dataset.trainerName;
            const peopleAmount = parseInt(this.dataset.peopleAmount);
            const remainingAmount = parseInt(this.dataset.remainingAmount);
            const price = parseInt(this.dataset.price);
            const rating = this.dataset.rating;
            const isDisabled = this.dataset.isDisabled === '1';
            const trainingId = this.dataset.trainingId;
            const weekday = this.dataset.weekday;
            const hour = this.dataset.hour;
            
            // Заполняем модальное окно
            modalTitle.textContent = `Занятие "${trainingName}"`;
            modalTitle.setAttribute('data-text', modalTitle.textContent);
            modalTrainer.textContent = `Тренер: ${trainerName}`;
            
            // Показываем количество мест только если тренировка не отключена
            if (!isDisabled) {
                modalPlaces.textContent = `Осталось мест: ${remainingAmount} из ${peopleAmount}`;
                modalPlaces.style.display = 'block';
            } else {
                modalPlaces.style.display = 'none';
            }
            
            // Формируем текст стоимости
            const priceText = price === 0 ? 'Включено в действующий абонемент' : `${price} руб.`;
            modalPrice.textContent = `Стоимость: ${priceText}`;
            modalRating.textContent = `Рейтинг: ${rating}`;
            
            // Заполняем скрытые поля формы
            signupTrainingId.value = trainingId;
            signupWeekday.value = weekday;
            signupHour.value = hour;
            
            // Настраиваем кнопку записи
            if (isDisabled || remainingAmount === 0) {
                signupButton.disabled = true;
                signupButton.textContent = 'Запись невозможна';
            } else {
                signupButton.disabled = false;
                signupButton.textContent = 'Записаться';
            }
            
            // Показываем модальное окно
            modalWindow.style.display = 'flex';
        });
    });    
});