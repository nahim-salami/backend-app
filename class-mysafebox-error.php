<?php

namespace MySafeBox;

class MySafeBoxError
{
    public static function triggerError($type)
    {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>ERROR OCCURED</title>
        </head>
        <body>
    
        <?php
        switch ($type) {
            case 'size_exceed':
                ?>
                <div>
                    <h2>La chaine de charactères est trop longue.</h2>
                </div>
                <?php
                break;
            case 'bd':
                                                                                                                                                    ob_start();
                ?>
                <div>
                    <h2>Impossible de se connecter à la base de donnée. Veuillez contacter l'administrateur</h2>
                </div>
                <?php

                break;
            case '404':
                                                                                                                                                    ob_start();
                ?>
                <div>
                    <h2>Cette page n'existe pas ou url incorrecte</h2>
                    <a href="<?=SITE_URL?>">Go to home</a>
                </div>
                <?php

                break;
            case '403':
                # code...


                break;
            default:
                                                                                                                                                    echo "An occured error found";

                break;
        }

        ?>
            </body>
        </html>
        <?php

        echo ob_get_clean();
    }
}
