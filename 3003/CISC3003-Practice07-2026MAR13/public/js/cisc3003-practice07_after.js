window.addEventListener('load', function () {
  var form = document.getElementById('mainForm');
  if (!form) {
    return;
  }

  // Attach highlight handlers for fields with the "hilightable" class
  var hilightable = document.querySelectorAll('.hilightable');

  for (var i = 0; i < hilightable.length; i++) {
    (function (el) {
      el.addEventListener('focus', function () {
        el.classList.add('highlight');
      });

      el.addEventListener('blur', function () {
        el.classList.remove('highlight');
      });
    })(hilightable[i]);
  }

  // Required fields must not be empty when submitting
  var required = document.querySelectorAll('.required');

  // When required fields change, clear error styling once they have content
  for (var j = 0; j < required.length; j++) {
    (function (el) {
      var clearError = function () {
        if (el.value.trim() !== '') {
          el.classList.remove('error');
        }
      };

      el.addEventListener('input', clearError);
      el.addEventListener('change', clearError);
    })(required[j]);
  }

  // Validate required fields on submit
  form.addEventListener('submit', function (event) {
    var isValid = true;

    for (var k = 0; k < required.length; k++) {
      var field = required[k];

      if (field.value.trim() === '') {
        field.classList.add('error');
        isValid = false;
      }
    }

    if (!isValid) {
      event.preventDefault();
    }
  });
});