document.addEventListener('DOMContentLoaded', function() {
    console.log("bienvenido a mi mierda");
    // Obtener todos los elementos <a> (enlaces) en la página
    var enlaces = document.querySelectorAll('a');

    // Iterar sobre cada enlace y reemplazar "http://" por "https://"
    enlaces.forEach(function(enlace) {
      var href = enlace.getAttribute('href');
      if (href && href.startsWith('http://')) {
        enlace.setAttribute('href', href.replace('http://', 'https://'));
      }
    });
  });