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
     *
     * @return bool|string
     */
    public function pagina(string $contenido, string $title = "Veterinaria San Francisco", array $scripsExtra = null, array $estilosExtra = null): bool|string
    {
        ob_start(); ?>

        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <link rel="icon" href="/public/assets/vete_icon.png">
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
        <?php
        if (isset($_SESSION['mensaje'])) {
            echo $_SESSION['mensaje'];
            unset($_SESSION['mensaje']);
        }
        ?>
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
        <?php return ob_get_clean();
    }

    /**
     * Imprime una tabla en pantalla
     *
     * @param array $array
     * @param string $titulo
     * @param string $subtitulo
     * @param string $encabezado
     *
     * @return bool|string
     */
    public function tabla(array $array, string $titulo = "", string $subtitulo = "", string $encabezado = ""): bool|string
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
                    if (empty($array["columnas"])) {
                        echo "<th>" . "Sin datos" . "</th>";

                    } else {
                        foreach ($array["columnas"] as $columna) {
                            echo "<th>" . ucfirst($columna) . "</th>";
                        }
                    }
                    ?>
                </tr>
                <?php
                if (!empty($array["filas"])) {
                    foreach ($array["filas"] as $fila) {
                        echo "<tr>";
                        foreach ($fila as $campo) {
                            echo "<td>" . $campo . "</td>";
                        }
                        echo "</tr>";
                    }
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
     *
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
     * Imprime botones de enviar y cancelar en un form
     *
     * @param string $etiquetaEnviar
     * @param string $valorSubmit
     * @param string $hrefCancelar
     * @param string $onclickCancelar
     *
     * @return bool|string
     */
    public
    function botonesForm(string $etiquetaEnviar = "Enviar", string $valorSubmit = "Submit", string $hrefCancelar = "/" . _MODULO, string $onclickCancelar = ""): bool|string
    {
        ob_start(); ?>
        <div class="w3-margin-top">
            <?= $this->botonSubmit($etiquetaEnviar, "", "w3-green", $valorSubmit, $valorSubmit); ?>
            <?= $this->boton("Cancelar", $hrefCancelar, $onclickCancelar, "", "w3-red"); ?>
        </div>
        <?php return ob_get_clean();
    }

    /**
     * Genera el HTML de un boton para enviar un form
     *
     * @param string $etiqueta Texto del boton
     * @param string $icono clase del icono (Font Awesome)
     * @param string $claseExtra Clases extra para el boton
     *
     * @return bool|string
     */
    public
    function botonSubmit(string $etiqueta, string $icono = "", string $claseExtra = "", $name = "submit", $valor = "submit"): bool|string
    {
        if (!empty($icono)) {
            $icono = "<i class=\"fa $icono\"></i>";
        }
        ob_start(); ?>
        <button type="submit" class="w3-btn w3-round <?= $claseExtra ?>" name="<?= $name ?>" value="<?= $valor ?>">
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
     * @param bool $requerido
     *
     * @return bool|string
     */
    public
    function campoFormTexto(string $name, string $etiqueta, string $placeHolder = "", bool $requerido = false): bool|string
    {
        $valor = !empty($_POST[$name]) ? $_POST[$name] : "";
        ob_start(); ?>
        <div class="w3-form">
            <label for="<?= $name ?>"><?= $etiqueta ?></label><br>
            <input class="w3-input w3-border w3-round w3-margin-top" type="text" id="<?= $name ?>"
                   name="<?= $name ?>"
                   placeholder="<?= $placeHolder ?>"<?php if ($requerido) echo "required"; ?> value="<?= $valor ?>">
            <br>
        </div>
        <?php return ob_get_clean();
    }

    /**
     * Genera el HTML de un campo tipo email
     *
     * @param string $name
     * @param string $etiqueta
     * @param string $placeHolder
     * @param bool $requerido
     *
     * @return bool|string
     */
    public
    function campoFormMail(string $name = "mail", string $etiqueta = "Mail", string $placeHolder = "Ingrese mail", bool $requerido = true): bool|string
    {
        $valor = !empty($_POST[$name]) ? $_POST[$name] : "";
        ob_start(); ?>
        <div class="w3-form">
            <label for="<?= $name ?>"><?= $etiqueta ?></label><br>
            <input class="w3-input w3-border w3-round w3-margin-top" type="email" id="<?= $name ?>"
                   name="<?= $name ?>"
                   placeholder="<?= $placeHolder ?>"<?php if ($requerido) echo "required"; ?> value="<?= $valor ?>"><br>
        </div>
        <?php return ob_get_clean();
    }

    /**
     * Genera el HTML de un input de tipo password
     *
     * @param string $name Name y ID del elemento
     * @param string $etiqueta Label
     * @param string $placeHolder
     * @param bool $requerido
     *
     * @return bool|string
     */
    public
    function campoFormPassword(string $name = "password", string $etiqueta = "Ingrese la clave", string $placeHolder = "Ingrese la clave", bool $requerido = true): bool|string
    {
        $valor = !empty($_POST[$name]) ? $_POST[$name] : "";
        ob_start(); ?>
        <div class="w3-form">
            <label for="<?= $name ?>"><?= $etiqueta ?></label><br>
            <input class="w3-input w3-border w3-round w3-margin-top" type="password" id="<?= $name ?>"
                   name="<?= $name ?>"
                   placeholder="<?= $placeHolder ?>" <?php if ($requerido) echo "required"; ?>
                   value="<?= $valor ?>"><br>
        </div>
        <?php return ob_get_clean();
    }

    /**
     * Genera el HTML de un input de tipo select
     *
     * @param string $name
     * @param string $etiqueta
     * @param array $opciones
     * @param bool $requerido
     *
     * @return bool|string
     */
    public
    function campoSelect(string $name, string $etiqueta, array $opciones, mixed $id_checked = null, bool $requerido = false): bool|string
    {
        $id_checked = !empty($_POST[$name]) && !empty($id_checked) ? $_POST[$name] : $id_checked;
        ob_start(); ?>
        <div class="w3-form">
            <label for="<?= $name ?>"><?= $etiqueta ?></label><br>
            <select class="w3-input w3-border w3-round w3-margin-top w3-select" name="<?= $name ?>"
                    id="<?= $name ?>" <?php if ($requerido) echo "required"; ?>>
                <option disabled selected>Seleccione</option>
                <?php
                $selected = "";
                foreach ($opciones as $clave => $valor) {
                    if ($clave == $id_checked) $selected = " selected";
                    echo "<option value=\"$clave\" $selected>" . ucwords($valor) . "</option>";
                    $selected = "";
                } ?>
            </select>
        </div>
        <?php return ob_get_clean();
    }

    /**
     * Genera el HTML de un input de tipo select
     *
     * @param string $name
     * @param string $etiqueta
     * @param array $opciones "valor"=>"nombre"
     * @param mixed|null $id_checked Si esta seteado, selecciona por default el id que se le pase
     * @param bool $requerido
     *
     * @return string
     */
    public
    function campoRadio(string $name, string $etiqueta, array $opciones, mixed $id_checked = null, bool $requerido = false): string
    {
        $id_checked = !empty($_POST[$name]) && !empty($id_checked) ? $_POST[$name] : $id_checked;
        ob_start(); ?>
        <label><?= $etiqueta ?></label>
        <div class="w3-form">

            <?php foreach ($opciones as $id => $valor) {
                printf('<p><input class="w3-radio" type="radio" name="%s" value="%s" %s%s><label> %s</label></p>'
                    , $name
                    , $id
                    , $id == $id_checked ? "checked " : ""
                    , $requerido ? "required " : ""
                    , ucfirst($valor)
                );
            } ?>

        </div>
        <?php return ob_get_clean();
    }
}