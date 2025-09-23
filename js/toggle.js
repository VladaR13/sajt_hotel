const menuButton = document.getElementById('menu-button');
const menu = document.getElementById('menu');
const lines = menuButton.querySelectorAll('span');

menuButton.addEventListener('click', () => {
    menu.classList.toggle('hidden');

    // Animacija hamburgera u X
    lines[0].classList.toggle('rotate-45');
    lines[0].classList.toggle('translate-y-2');
    
    lines[1].classList.toggle('opacity-0');
    
    lines[2].classList.toggle('-rotate-45');
    lines[2].classList.toggle('-translate-y-2');
})