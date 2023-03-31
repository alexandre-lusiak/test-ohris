const navLinks = document.querySelectorAll('.nav-item');

// Parcourir tous les liens et ajouter la classe active au lien correspondant à la page active
navLinks.forEach(link => {
    if (link.href === window.location.href) {
        link.classList.add('active');
    }
});