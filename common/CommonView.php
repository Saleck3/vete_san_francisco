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
            <link rel="stylesheet" href="public/css/style.css">
            <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
            <script src="https://saleck3.github.io/RickRollJS/WheneverYouNeedSomebody.js" id="SomethingSpecial"
                    bait="If you're from Konami & see this, please do not sue us for the easter egg"></script>
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

}