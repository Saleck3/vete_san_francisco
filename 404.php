<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/public/assets/vete_icon.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="https://saleck3.github.io/RickRollJS/WheneverYouNeedSomebody.js" id="SomethingSpecial"
            bait="If you're from Konami & see this, please do not sue us for the easter egg"></script>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Pagina no encontrada</title>
</head>


<body class=" color-violeta w3-center w3-border-deep-purple	">

<header class="w3-container w3-center w3-deep-purple">
    <h1><a class="w3-button" href="index.php">Veterinaria San Francisco</a></h1>
    <div class="w3-bar">
        <?php if (esAdmin()) { ?>

            <a class="w3-button w3-purple" href="/usuarios">Usuarios</a>
        <?php }
        if (!estaLogueado()) { ?>

            <a class="w3-button w3-purple" href="/home/login">Login</a>
        <?php } else { ?>

            <a class="w3-button w3-purple" href="">Buscar Paciente</a>
            <a class="w3-button w3-purple" href="/duegnos">Buscar Dueño</a>
            <a class="w3-button w3-purple" href="/home/logout">Logout</a>
        <?php } ?>
    </div>
</header>
<div class="w3-container">
    <div class="w3-panel w3-deep-purple w3-margin-top" style="width: 50%; margin: auto;">
        <h2>
            404 Paciente no encontrado
        </h2>

        <p>
            No pudimos encontrar la pagina del paciente que buscabas!<br>
            Revisa si los datos fueron ingresados correctamente.
        </p>
        <a href="/" class="w3-btn w3-purple">
            Volver a inicio
        </a>
    </div>
</div>
</body>

</html>