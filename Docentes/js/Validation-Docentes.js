jQuery(document).ready(function ($) {
  $.validator.addMethod(
    "emailOnly",
    function (value, element) {
      return (
        this.optional(element) ||
        /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(value)
      );
    },
    "Por favor ingresa un correo válido."
  );

  $.validator.addMethod(
    "lettersOnly",
    function (value, element) {
      return this.optional(element) || /^[a-zA-ZáéíóúÁÉÍÓÚÑñ\s]+$/.test(value);
    },
    "Por favor, ingrese solo letras."
  );

  // Formulario crear
  $("#formDocente").validate({
    rules: {
      tipo_documento: { required: true },
      numero_documento: {
        required: true,
        digits: true,
        minlength: 6,
        maxlength: 10,
      },
      nombres: { required: true, lettersOnly: true },
      apellidos: { required: true, lettersOnly: true },
      perfil_profesional: {}, // opcional
      telefono: { digits: true, minlength: 10, maxlength: 10 }, // opcional
      direccion: {}, // opcional
      email: { required: true, emailOnly: true },
    },
    messages: {
      tipo_documento: {
        required: "Por favor selecciona un tipo de documento.",
      },
      numero_documento: {
        required: "Por favor ingresa un número de documento.",
        digits: "Solo se permiten números.",
        minlength: "Debe contener mínimo 6 dígitos.",
        maxlength: "Debe contener máximo 10 dígitos.",
      },
      nombres: { required: "Por favor ingresa tu nombre." },
      apellidos: { required: "Por favor ingresa tu apellido." },
      email: {
        required: "Por favor ingresa un correo electrónico.",
        emailOnly: "Por favor ingresa un correo válido.",
      },
    },
    submitHandler: function (form) {
      crearDocente();
    },
  });

  // Formulario editar
  $("#editForm").validate({
    rules: {
      tipo_documento: { required: true },
      numero_documento: { required: true, digits: true },
      nombres: { required: true, lettersOnly: true },
      apellidos: { required: true, lettersOnly: true },
      perfil_profesional: {}, // opcional
      telefono: { digits: true, minlength: 10, maxlength: 10 }, // opcional
      direccion: {}, // opcional
      email: { required: true, emailOnly: true },
    },
    messages: {
      tipo_documento: {
        required: "Por favor selecciona un tipo de documento.",
      },
      numero_documento: {
        required: "Por favor ingresa un número de documento.",
        digits: "Solo se permiten números.",
      },
      nombres: { required: "Por favor ingresa tu nombre." },
      apellidos: { required: "Por favor ingresa tu apellido." },
      email: {
        required: "Por favor ingresa un correo electrónico.",
        emailOnly: "Por favor ingresa un correo válido.",
      },
    },
    submitHandler: function (form) {
      guardarCambiosDocente();
    },
  });

  // Contadores de caracteres para descripción de especialidad
  $("#descripcion_especialidad, #descripcion_especialidad_edit").on(
    "input",
    function () {
      const maxLength = $(this).attr("maxlength");
      const restantes = maxLength - $(this).val().length;
      const contadorId =
        $(this).attr("id") === "descripcion_especialidad"
          ? "#contadorCrear"
          : "#contadorEditar";

      $(contadorId).text(`${restantes} caracteres disponibles`);
      if (restantes <= 20) {
        $(contadorId).addClass("alerta");
      } else {
        $(contadorId).removeClass("alerta");
      }
    }
  );
});
