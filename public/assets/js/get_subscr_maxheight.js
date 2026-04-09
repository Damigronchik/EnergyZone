document.addEventListener('DOMContentLoaded', () => {
    const parent = document.querySelector('.subscriptions__cards');
    const kids = parent.children;
    let maxHeight = 0;
    
    for(i = 0; i < kids.length; i++) {
        if (kids[i].style.height > maxHeight) {
            maxHeight = kids[i].style.height
        }
    }

    parent.style.height = maxHeight+2;
    Array.from(kids).forEach(kid => {
        kid.style.height = '100%';
    });
})