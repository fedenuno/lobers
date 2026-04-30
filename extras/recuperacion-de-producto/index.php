<?php
    date_default_timezone_set('America/Mexico_City');
    set_time_limit(0);
    require 'vendor/autoload.php'; 
    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    try {
        $spreadsheet = new Spreadsheet();
        $inputFileType = 'Xlsx';
        $inputFileName = 'documentos/Ejemplo lista Syscom Productos (1).xlsx';
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $reader->setReadDataOnly(true);
        $worksheetData = $reader->listWorksheetInfo($inputFileName);
        $sheet = $spreadsheet->getActiveSheet();

        $importar = [['Handle','Title','Body (HTML)','Vendor','Product Category','Type','Tags','Published','Option1 Name','Option1 Value','Option1 Linked To','Option2 Name','Option2 Value','Option2 Linked To','Option3 Name','Option3 Value','Option3 Linked To','Variant SKU','Variant Grams','Variant Inventory Tracker','Variant Inventory Qty','Variant Inventory Policy','Variant Fulfillment Service','Variant Price','Variant Compare At Price','Variant Requires Shipping','Variant Taxable','Variant Barcode','Image Src','Image Position','Image Alt Text','Gift Card','SEO Title','SEO Description','Google Shopping / Google Product Category','Google Shopping / Gender','Google Shopping / Age Group','Google Shopping / MPN','Google Shopping / Condition','Google Shopping / Custom Product','Google Shopping / Custom Label 0','Google Shopping / Custom Label 1','Google Shopping / Custom Label 2','Google Shopping / Custom Label 3','Google Shopping / Custom Label 4','Variant Image','Variant Weight Unit','Variant Tax Code','Cost per item','Status']];

        foreach ($worksheetData as $hoja=>$worksheet) {
            if($hoja == 0) {
                $sheetName = $worksheet['worksheetName'];
                $reader->setLoadSheetsOnly($sheetName);
                $spreadsheet = $reader->load($inputFileName);

                $worksheet = $spreadsheet->getActiveSheet();
                $datos = $worksheet->toArray();
                foreach ($datos as $key => $value) {
                    if($key > 1) {
                        $importar[] = ['',limpiar($value[2]),limpiar($value[2]),$value[1],'','',$value[0],'true','','','','','','','','','',$value[0],'','',$value[3],'deny','manual',$value[7],$value[7],'true','true','',$value[8],1,'','','','','','','','','','','','','','','','','KG','','','active'];
                    }
                }
            }
        }

        $fecha = date('dMY_His');
        $nombreArchivo = "descargas/$fecha.csv";
        if ($archivo = fopen($nombreArchivo, 'w')) {
            foreach ($importar as $fila) {
                fputcsv($archivo, $fila);
            }

            fclose($archivo);

            echo "Archivo CSV <a href=\"$nombreArchivo\">$fecha</a> creado correctamente.<br>";
        } else {
            echo "No se pudo abrir el archivo $fecha para escritura.<br>";
        }
    } catch(Exception $e) {}

    function limpiar($texto) {
        return str_replace(['i?n','C?m','c?m','M?l','m?l','l?','g?a','trav?s','se?al','360�','�','v?a','R?p','r?p','e?o','A?os','r?a','?ptica','t?a','n?c','n?t','Micr?fono','F?cil','f?cil','Mult?metro','mult?metro','?rea','h?a','an?loga','di?metro','Bot?n'],
                           ['ión','Cám','cám','Múl','múl','lá','gía','través','señal','360°','','vía','Ráp','ráp','eño','Años','ría','óptica','tía','núc','nét','Micrófono','Fácil','fácil','Multímetro','multímetro','área','hía','análoga','diámetro','Botón'], 
                           mb_convert_encoding($texto, 'ISO-8859-1', 'UTF-8'));
    }
?>