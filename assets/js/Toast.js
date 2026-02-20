document.addEventListener('Turbo:load', () => {
    const toasts = document.querySelectorAll('.toast');
    toasts.forEach((toast, index) => {
        setTimeout(() => {
            toast.classList.add('opacity-0'); // Ajoute une classe pour l'animation
            setTimeout(() => {
                toast.remove(); // Supprime l'élément après l'animation
            }, 500); // Durée de l'animation (500ms)
        }, 10000 + (index * 2000)); // 10s pour le premier toast, puis +1s pour chaque toast suivant
    });
});
