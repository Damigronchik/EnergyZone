document.querySelectorAll('[class*="title"]').forEach(element => {
    element.setAttribute('data-text', element.textContent)
})