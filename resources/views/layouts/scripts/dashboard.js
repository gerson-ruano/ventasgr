document.addEventListener('DOMContentLoaded', function() {
    var cardBody = document.getElementById('cardBody');

        // Muestra el card-body
    cardBody.style.display = 'block';

        // Oculta el card-body después de 5 segundos (5000 milisegundos)
     setTimeout(function() {
        cardBody.style.display = 'none';
    }, 3000);
});
