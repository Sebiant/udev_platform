jQuery(document).ready(function ($) {
  const maxChars = 150; // Nuevo límite para nombre y descripción

  // Validación del formulario de creación
  $("#formMateria").validate({
    rules: {
      tipo: { required: true },
      nombre: { required: true, minlength: 3, maxlength: maxChars },
      id_programa: { required: true },
      descripcion: { required: true, maxlength: maxChars },
    },
    messages: {
      tipo: { required: "Por favor, ingresa el tipo de la materia." },
      nombre: {
        required: "Por favor, ingresa el nombre de la materia.",
        minlength: "El nombre debe tener al menos 3 caracteres.",
        maxlength: `El nombre no puede superar los ${maxChars} caracteres.`,
      },
      id_programa: { required: "Por favor, ingresa el programa." },
      descripcion: {
        required: "Por favor, ingresa una descripción.",
        maxlength: `La descripción no puede superar los ${maxChars} caracteres.`,
      },
    },
    submitHandler: function (form) {
      console.log("Formulario validado y listo para enviar.");
      form.submit();
      crearMateria();
    },
  });

  // Validación del formulario de edición
  $("#editForm").validate({
    rules: {
      tipo: { required: true },
      nombre: { required: true, minlength: 3, maxlength: maxChars },
      id_programa: { required: true },
      descripcion: { required: true, maxlength: maxChars },
    },
    messages: {
      tipo: { required: "Por favor, ingresa el tipo de la materia." },
      nombre: {
        required: "Por favor, ingresa el nombre de la materia.",
        minlength: "El nombre debe tener al menos 3 caracteres.",
        maxlength: `El nombre no puede superar los ${maxChars} caracteres.`,
      },
      id_programa: { required: "Por favor, ingresa el programa." },
      descripcion: {
        required: "Por favor, ingresa una descripción.",
        maxlength: `La descripción no puede superar los ${maxChars} caracteres.`,
      },
    },
    submitHandler: function (form) {
      console.log("Formulario validado y listo para enviar.");
      form.submit();
      GuardarMateria();
    },
  });

  // Contador de caracteres en el formulario de creación
  $("#nombre, #descripcion").on("input", function () {
    const restantes = maxChars - $(this).val().length;
    let contadorId =
      $(this).attr("id") === "nombre"
        ? "#contadorNombreCrear"
        : "#contadorDescripcionCrear";
    $(contadorId).text(`${restantes} caracteres disponibles`);

    if (restantes <= 20) $(contadorId).addClass("alerta");
    else $(contadorId).removeClass("alerta");
  });

  // Contador de caracteres en el formulario de edición
  $("#nombre_edit, #descripcion_edit").on("input", function () {
    const restantes = maxChars - $(this).val().length;
    let contadorId =
      $(this).attr("id") === "nombre_edit"
        ? "#contadorNombreEditar"
        : "#contadorDescripcionEditar";
    $(contadorId).text(`${restantes} caracteres disponibles`);

    if (restantes <= 20) $(contadorId).addClass("alerta");
    else $(contadorId).removeClass("alerta");
  });
});
