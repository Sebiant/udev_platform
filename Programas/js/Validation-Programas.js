jQuery(document).ready(function ($) {
  $("#formPrograma").validate({
    rules: {
      tipo: {
        required: true,
        minlength: 3,
      },
      nombre: {
        required: true,
        minlength: 3,
      },
      descripcion: {
        required: true,
        maxlength: 150,
      },
    },
    messages: {
      tipo: {
        required: "Por favor, ingresa el tipo de programa.",
        minlength: "Debe tener al menos 3 caracteres.",
      },
      nombre: {
        required: "Por favor, ingresa el nombre.",
        minlength: "Debe tener al menos 3 caracteres.",
      },
      descripcion: {
        required: "Por favor, ingresa una descripción.",
        maxlength: "No puede exceder 150 caracteres.",
      },
    },
    submitHandler: function (form) {
      console.log("Formulario validado y listo para enviar.");
      form.submit();
      crearPrograma();
    },
  });

  $("#editForm").validate({
    rules: {
      tipo: {
        required: true,
        minlength: 3,
      },
      nombre: {
        required: true,
        minlength: 3,
      },
      descripcion: {
        required: true,
        maxlength: 150,
      },
    },
    messages: {
      tipo: {
        required: "Por favor, ingresa el tipo de programa.",
        minlength: "Debe tener al menos 3 caracteres.",
      },
      nombre: {
        required: "Por favor, ingresa el nombre.",
        minlength: "Debe tener al menos 3 caracteres.",
      },
      descripcion: {
        required: "Por favor, ingresa una descripción.",
        maxlength: "No puede exceder 150 caracteres.",
      },
    },
    submitHandler: function (form) {
      console.log("Formulario validado y listo para enviar.");
      form.submit();
      editarPrograma();
    },
  });

  // Contadores de caracteres
  $("#descripcion").on("input", function () {
    const maxLength = $(this).attr("maxlength");
    const restantes = maxLength - $(this).val().length;
    $("#contadorCrear").text(`${restantes} caracteres disponibles`);

    if (restantes <= 20) {
      $("#contadorCrear").addClass("alerta");
    } else {
      $("#contadorCrear").removeClass("alerta");
    }
  });

  $("#descripcion_edit").on("input", function () {
    const maxLength = $(this).attr("maxlength");
    const restantes = maxLength - $(this).val().length;
    $("#contadorEditar").text(`${restantes} caracteres disponibles`);

    if (restantes <= 20) {
      $("#contadorEditar").addClass("alerta");
    } else {
      $("#contadorEditar").removeClass("alerta");
    }
  });
});
