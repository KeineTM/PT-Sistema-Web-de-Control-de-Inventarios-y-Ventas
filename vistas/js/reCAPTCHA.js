/**
 * Información de la documentación de Google:
 * https://developers.google.com/recaptcha/docs/loading?hl=es-419
 */
grecaptcha.ready(function() {
  grecaptcha.execute('6LfZkFIlAAAAAAgFDKJkqcOVUlJcAVuM1mEvO9CI', {
    action: 'formularioLogin'
  }).then(function(token) {
    var recaptchaResponse = document.getElementById('recaptchaResponse');
    recaptchaResponse.value = token;
  });
});