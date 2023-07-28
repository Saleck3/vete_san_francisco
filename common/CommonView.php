<?php

class CommonView
{
    protected Logger $logger;

    public function __construct()
    {
        $this->logger = new Logger();
    }

    /**
     * Imprime una pagina con el estilo general
     *
     * @param string $contenido Cuerpo de la pagina,a inlcluir dentro de un w3-container
     * @param string $title Titulo de la pagina
     * @param array|null $scripsExtra array de Hrefs a scripts a agregar
     * @param array|null $estilosExtra array de Hrefs a estilos a agregar
     * @return bool|string
     */
    public function pagina(string $contenido, string $title = "Veterinaria San Francisco", array $scripsExtra = null, array $estilosExtra = null): bool|string
    {
        ob_start(); ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?= $title ?></title>
            <link rel="stylesheet" href="/public/css/style.css">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
            <script src="https://saleck3.github.io/RickRollJS/WheneverYouNeedSomebody.js" id="SomethingSpecial"
                    bait="If you're from Konami & see this, please do not sue us for the easter egg"></script>
            <link rel="stylesheet"
                  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
            <?php
            if (!empty($scripsExtra)) foreach ($scripsExtra as $script) {
                echo '<script src="' . $script . '" id="SomethingSpecial"></script>';
            }
            if (!empty($estilosExtra)) foreach ($estilosExtra as $estilo) {
                echo '<link rel="stylesheet" href="' . $estilo . '">';
            }
            ?>
        </head>
        <body class=" color-violeta w3-border-deep-purple	">
        <?= $this->menu() ?>
        <div class="w3-container">
            <?= $contenido ?>
        </div>
        </body>

        </html>
        <?php return ob_get_clean();
    }

    /**
     * Imprime el menu con todas sus opciones
     *
     * @return bool|string
     */
    public function menu(): bool|string
    {
        ob_start(); ?>
        <header class="w3-container w3-center w3-deep-purple">
            <h1><a class="w3-button" href="index.php">Veterinaria San Francisco</a></h1>
            <div class="w3-bar">
                <a class="w3-button w3-purple" href="">Buscar Paciente</a>
                <a class="w3-button w3-purple" href="">Buscar Dueño</a>
            </div>
        </header>
        <?php return ob_get_clean();
    }

    /**
     * Imprime una tabla en pantalla
     *
     * @param array $array
     * @param string $titulo
     * @param string $subtitulo
     * @return bool|string
     */
    public function tabla(array $array, string $titulo = "", string $subtitulo = "", $encabezado = ""): bool|string
    {
        ob_start(); ?>
        <div>
            <h1><?= $titulo ?></h1>
            <h3><?= $subtitulo ?></h3>
        </div>
        <?= $encabezado ?>
        <div class="w3-responsive w3-margin-top">
            <table class="w3-table-all w3-hoverable">
                <tr class="w3-light-blue">
                    <?php
                    foreach ($array["columnas"] as $columna) {
                        echo "<th>" . ucfirst($columna) . "</th>";
                    }
                    ?>
                </tr>
                <?php
                foreach ($array["filas"] as $fila) {
                    echo "<tr>";
                    foreach ($fila as $campo) {
                        echo "<td>" . $campo . "</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <?php return $this->pagina(ob_get_clean());
    }

    /**
     * Genera el html de un boton generico
     *
     * @param string $etiqueta Texto del boton
     * @param string $href Link al que redirigir
     * @param string $onClick Funcion a ejecutar
     * @param string $icono clase del icono (Font Awesome)
     * @param string $claseExtra Clases extra para el link
     * @return bool|string
     */
    public function boton(string $etiqueta, string $href = "", string $onClick = "", string $icono = "", string $claseExtra = ""): bool|string
    {
        if (!empty($icono)) {
            $icono = "<i class=\"fa $icono\"></i>";
        }
        ob_start(); ?>
        <a class="w3-btn w3-round <?= $claseExtra ?>" href="<?= $href; ?>" onclick="<?= $onClick; ?>">
            <?= $icono . " " . $etiqueta ?>
        </a>
        <?php return ob_get_clean();
    }

    /**
     * Genera el HTML de un boton para enviar un form
     *
     * @param string $etiqueta Texto del boton
     * @param string $icono clase del icono (Font Awesome)
     * @param string $claseExtra Clases extra para el boton
     * @return bool|string
     */
    public function boton_submit(string $etiqueta, string $icono = "", string $claseExtra = ""): bool|string
    {
        if (!empty($icono)) {
            $icono = "<i class=\"fa $icono\"></i>";
        }
        ob_start(); ?>
        <button type="submit" class="w3-btn w3-round <?= $claseExtra ?>" name="submit" value="submit">
            <?= $icono . " " . $etiqueta ?>
        </button>
        <?php return ob_get_clean();
    }

    /**
     * Genera el HTML de un input de texto
     *
     * @param string $name Name y ID del elemento
     * @param string $etiqueta Label
     * @param string $placeHolder
     * @return bool|string
     */
    public function campo_form_texto(string $name, string $etiqueta, string $placeHolder = ""): bool|string
    {
        ob_start(); ?>
        <div class="w3-form"
        <label for="<?= $name ?>"><?= $etiqueta ?></label><br>
        <input class="w3-input w3-border w3-round w3-margin-top" type="text" id="<?= $name ?>" name="<?= $name ?>"
               placeholder="<?= $placeHolder ?>"><br>
        <?php return ob_get_clean();
    }

    /**
     * Genera el HTML de un input de tipo password
     *
     * @param string $name Name y ID del elemento
     * @param string $etiqueta Label
     * @param string $placeHolder
     * @return bool|string
     */
    public function campo_form_password(string $name, string $etiqueta, string $placeHolder = ""): bool|string
    {
        ob_start(); ?>
        <div class="w3-form"
        <label for="<?= $name ?>"><?= $etiqueta ?></label><br>
        <input class="w3-input w3-border w3-round w3-margin-top" type="password" id="<?= $name ?>" name="<?= $name ?>"
               placeholder="<?= $placeHolder ?>"><br>
        <?php return ob_get_clean();
    }

    public function campo_select(string $name, string $etiqueta, array $datos): bool|string
    {
        ob_start(); ?>
        <label for="<?= $name ?>"><?= $etiqueta ?></label><br>
        <select class="w3-input w3-border w3-round w3-margin-top w3-select" name="<?= $name ?>" id="<?= $name ?>">
            <option disabled selected>Seleccione</option>
            <?php foreach ($datos as $clave => $valor) {
                echo "<option value=\"$clave\">" . ucwords($valor["nombre"]) . "</option>";
            } ?>
        </select>
        <?php return ob_get_clean();
    }

}