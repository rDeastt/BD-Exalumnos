<?php
session_start();
require_once __DIR__ . '/../models/Exalumno.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['correo']) || $_SESSION['admin'] == 1) {
    header('Location: login');
    exit;
}

// Obtener correo y código de la sesión
$correo = $_SESSION['correo'];
$codigo = $_SESSION['codigo']; // Asegurarse de que el código se haya guardado en la sesión al iniciar sesión

// Verificar si ya existe un registro de exalumno asociado al correo
$exalumno = Exalumno::obtenerPorCorreo($correo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/exalumnos/assets/img/favicon.ico">
    <title>Actualización de Datos de Exalumnos</title>
    <link rel="stylesheet" href="/exalumnos/assets/css/form.css">
</head>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paisSelect = document.getElementById('pais_residencia');
    const celularInput = document.getElementById('celular');
    const anioRetiroInput = document.getElementById('anio_retiro');
    const anioPromocionInput = document.getElementById('anio_promocion');

    // Obtener el valor de teléfono guardado en la base de datos
    const telefonoGuardado = '<?php echo $exalumno ? $exalumno['celular'] : ''; ?>';

    // Llamar al JSON local para obtener los países y sus códigos de marcado
    fetch('/exalumnos/assets/data/countries.json')
        .then(response => response.json())
        .then(data => {
            // Ordenar los países alfabéticamente por nameES
            data.sort((a, b) => a.nameES.localeCompare(b.nameES));

            // Iterar sobre los países ordenados y agregarlos al select
            data.forEach(country => {
                const option = document.createElement('option');
                option.value = country.nameES;
                option.text = `${country.nameES} (+${country.phoneCode})`;
                option.setAttribute('data-code', `+${country.phoneCode}`);

                // Seleccionar país guardado
                if ('<?php echo $exalumno ? $exalumno['pais_residencia'] : ''; ?>' === country.nameES) {
                    option.selected = true;
                    if (!telefonoGuardado) {
                        celularInput.value = `+${country.phoneCode} `;
                    }
                }

                paisSelect.appendChild(option);
            });

            if (telefonoGuardado) {
                celularInput.value = telefonoGuardado;
            }
        })
        .catch(error => console.error('Error al cargar los países:', error));

    // Al seleccionar un país, actualizar el código del teléfono
    paisSelect.addEventListener('change', function() {
        const selectedOption = paisSelect.options[paisSelect.selectedIndex];
        const countryCode = selectedOption.getAttribute('data-code');

        if (countryCode) {
            celularInput.value = `${countryCode} `;
            celularInput.focus();
        }
    });

    // Restricción para el campo de celular (solo +, números y espacios)
    celularInput.addEventListener('input', function() {
        celularInput.value = celularInput.value.replace(/[^\d\s+]/g, '');
    });

    // Validar el campo de año de retiro y promoción (solo números, máx. 4 caracteres)
    const validarAnio = (input) => {
        input.value = input.value.replace(/\D/g, '').slice(0, 4);
    };

    anioRetiroInput.addEventListener('input', () => validarAnio(anioRetiroInput));
    anioPromocionInput.addEventListener('input', () => validarAnio(anioPromocionInput));
});
</script>


<body>
    <div class="background-container">
        <div class="form-container">
            <!-- Logo encima del título -->
            <div class="logo-container">
                <img src="/exalumnos/assets/img/logo.avif" alt="Logo" class="logo">
            </div>
            
            <h2>Actualización de Datos de Exalumnos</h2>
            <p>Agradeceremos su colaboración para actualizar nuestra base de datos llenando el siguiente formulario que le tomará un aproximado de 2 minutos. Esta información servirá no solo para actualizar su información en el colegio, sino para que tanto usted como las delegadas puedan ubicar a uno o varios exalumnas/os cuando sea necesario. Asimismo permitirá, sólo con las personas que lo autoricen, compartir sus datos con otros/as exalumnos/as que requieran de sus servicios de acuerdo al cargo que señale en la empresa donde labora.</p>
            <form action="processform" method="POST">
                <!-- Código (no editable) -->
                <label for="codigo">Código de exalumno:</label>
                <input type="text" name="codigo" value="<?php echo htmlspecialchars($codigo); ?>" readonly>

                <!-- Correo electrónico (no editable) -->
                <label for="correo">Correo electrónico:</label>
                <input type="email" name="correo" value="<?php echo htmlspecialchars($correo); ?>" readonly>

                <!-- Política de privacidad -->
                <label for="politica">Políticas de privacidad:</label>
                <p class="politica">
                    De conformidad con la Ley de Protección de Datos Personales (Ley 29733) y su Reglamento (Decreto Supremo 003-2013-JUS), 
                    otorgo mi consentimiento expreso, previo, informado e inequívoco a LA ASOCIACIÓN COLEGIO 
                    MARÍA ALVARADO para la recopilación y tratamiento de datos personales propios, con uso exclusivo para la actualización de base de datos de exalumnos del plantel.
                    <br>
                    <a href="https://cma.edu.pe/privacy-statement/" target="_blank" style="color: inherit; text-decoration: none;"><strong>Seleccione aquí para ver las políticas de privacidad</strong>.</a>
                </p>
                <input type="checkbox" name="politica" <?php echo $exalumno && $exalumno['politica'] ? 'checked' : ''; ?> required> Acepto
                
                <!-- Documento de Identidad -->
                <label for="documentoidentidad">Documento de identidad:</label>
                <input type="text" name="documentoidentidad" value="<?php echo $exalumno ? htmlspecialchars($exalumno['documentoidentidad']) : ''; ?>" required>

                <!-- Término LHS -->
                <label for="termino_lhs">¿Término sus estudios en el Colegio María Alvarado?:</label>
                <select name="termino_lhs" required>
                    <option value="Sí" <?php echo $exalumno && $exalumno['termino_lhs'] == 'Sí' ? 'selected' : ''; ?>>Sí</option>
                    <option value="No" <?php echo $exalumno && $exalumno['termino_lhs'] == 'No' ? 'selected' : ''; ?>>No</option>
                </select>

                <!-- Año de retiro -->
                <label for="anio_retiro">Año de retiro:</label>
                <input type="text" id="anio_retiro" name="anio_retiro" value="<?php echo $exalumno ? htmlspecialchars($exalumno['anio_retiro']) : ''; ?>" placeholder="Ej: 1996" maxlength="4" required>

                <!-- Color de promoción -->
                <label for="color_promocion">Color de su promoción:</label>
                <select name="color_promocion" required>
                    <option value="Red" <?php echo $exalumno && $exalumno['color_promocion'] == 'Red' ? 'selected' : ''; ?>>Red</option>
                    <option value="Green" <?php echo $exalumno && $exalumno['color_promocion'] == 'Green' ? 'selected' : ''; ?>>Green</option>
                    <option value="Pink" <?php echo $exalumno && $exalumno['color_promocion'] == 'Pink' ? 'selected' : ''; ?>>Pink</option>
                    <option value="Blue" <?php echo $exalumno && $exalumno['color_promocion'] == 'Blue' ? 'selected' : ''; ?>>Blue</option>
                    <option value="Yellow" <?php echo $exalumno && $exalumno['color_promocion'] == 'Yellow' ? 'selected' : ''; ?>>Yellow</option>
                </select>

                <!-- Año de promoción -->
                <label for="anio_promocion">Año de promoción:</label>
                <input type="text" id="anio_promocion" name="anio_promocion" value="<?php echo $exalumno ? htmlspecialchars($exalumno['anio_promocion']) : ''; ?>" placeholder="Ej: 2010" maxlength="4" required>

                <!-- Delegado de promoción (cambiar a combobox) -->
                <label for="delegado_promocion">¿Es delegado(a) de su promoción?:</label>
                <select name="delegado_promocion" required>
                    <option value="Sí" <?php echo $exalumno && $exalumno['delegado_promocion'] == 'Sí' ? 'selected' : ''; ?>>Sí</option>
                    <option value="No" <?php echo $exalumno && $exalumno['delegado_promocion'] == 'No' ? 'selected' : ''; ?>>No</option>
                </select>

                <!-- Apellidos -->
                <label for="apellidos">Apellidos (Paternos y Maternos):</label>
                <input type="text" name="apellidos" value="<?php echo $exalumno ? htmlspecialchars($exalumno['apellidos']) : ''; ?>" required>

                <!-- Nombres -->
                <label for="nombres">Nombres:</label>
                <input type="text" name="nombres" value="<?php echo $exalumno ? htmlspecialchars($exalumno['nombres']) : ''; ?>" required>

                <!-- País de residencia -->
                <label for="pais_residencia">País de residencia:</label>
                <select id="pais_residencia" name="pais_residencia" required>
                    <option value="">Selecciona un país</option>
                </select>

                <!-- Celular -->
                <label for="celular">Celular:</label>
                <input type="text" id="celular" name="celular" value="<?php echo $exalumno ? htmlspecialchars($exalumno['celular']) : ''; ?>" required>

                <!-- Fecha de nacimiento -->
                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                <input type="date" name="fecha_nacimiento" value="<?php echo $exalumno ? htmlspecialchars($exalumno['fecha_nacimiento']) : ''; ?>" required>

                <!-- Profesión -->
                <label for="profesion">Profesión:</label>
                <input type="text" name="profesion" value="<?php echo $exalumno ? htmlspecialchars($exalumno['profesion']) : ''; ?>" required>

                <!-- Empresa -->
                <label for="empresa_labora">Empresa en la que labora actualmente:</label>
                <input type="text" name="empresa_labora" value="<?php echo $exalumno ? htmlspecialchars($exalumno['empresa_labora']) : ''; ?>">

                <!-- Cargo -->
                <label for="cargo">Cargo:</label>
                <input type="text" name="cargo" value="<?php echo $exalumno ? htmlspecialchars($exalumno['cargo']) : ''; ?>">

                <!-- Permiso de compartir datos (cambiar a combobox) -->
                <label for="permiso">¿Brinda el permiso para compartir sus datos con otros exalumnos/as?:</label>
                <select name="permiso" required>
                    <option value="1" <?php echo $exalumno && $exalumno['permiso'] == 1 ? 'selected' : ''; ?>>Sí</option>
                    <option value="0" <?php echo $exalumno && $exalumno['permiso'] == 0 ? 'selected' : ''; ?>>No</option>
                </select>

                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
</body>
</html>