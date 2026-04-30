<?php
    $archivo = fopen('lista_de_precios_sedavi_231025.csv', 'r');
    $fila = 0;
    $filas = [];
    while (($datos = fgetcsv($archivo, 10000, ",")) == true) {
        if($fila >= 1) {
            $datos[0] = mb_convert_encoding($datos[0], 'UTF-8', 'ISO-8859-1');
            $datos[1] = str_replace(['--'],['-'], str_replace(['á','é','í','ó','ú','ñ','/','-','(',')',' '], ['a','e','i','o','u','n','','','','','-'], strtolower(trim($datos[0]))));
            $datos[2] = mb_convert_encoding($datos[2], 'UTF-8', 'ISO-8859-1');
            $datos[33] = str_replace(['https://tvc.mxhttps://cdn.tvc.mx'],['https://cdn.tvc.mx'], $datos[33]);
            $etiquetas = [$datos[3]];
            $aux = explode(' ', str_replace([','], [''], $datos[4]));
            foreach($aux as $key=>$value) {
                if($value != 'DE' && $value != 'Y' && $value != 'E' && $value != '/' && $value != '') {
                    $etiquetas[] = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
                }
            }

            $aux = explode(' ', str_replace([','], [''], $datos[5]));
            foreach($aux as $key=>$value) {
                if($value != 'DE' && $value != 'Y' && $value != 'E' && $value != '/' && $value != '') {
                    $etiquetas[] = mb_convert_encoding($value, 'UTF-8', 'ISO-8859-1');
                }
            }

            $datos[6] = implode(',',$etiquetas);
        }

        
        $filas[] = $datos;
        $fila++;
    }
    fclose($archivo);

    $fp = fopen('./catalogo-shopify-sedavi.csv', 'w');
    // fputs($fp, "\xEF\xBB\xBF");
    foreach ($filas as $row) {
        fputcsv($fp, $row);
    }
    fclose($fp);
?>