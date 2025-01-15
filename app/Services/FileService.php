<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

class FileService
{
    public function copyDolibarrFiles($doc_root)
    {
        $zip_file = base_path('database/dolibarr-20.zip');
        $zip = new \ZipArchive();
        if ($zip->open($zip_file) === TRUE) {
            $zip->extractTo($doc_root);
            $zip->close();
            return true;
        } else {
            \Log::error("Erreur lors de la décompression du fichier Dolibarr");
            
            return false;
        }
    }

    public function updateConfiguration($doc_root, $instance_name, $db_name)
    {
        $filename = $doc_root . '/conf/conf.php';
        
        $url_main_root = 'http://www.' . $instance_name . Config::get('dolibarr.domain_suffix');
        $doc_main_root_alt = $doc_root . '/custom';

        if (File::exists($filename)) {
            $content = "\n\$dolibarr_main_db_name = \"$db_name\";\n";
            $content .= "\$dolibarr_main_url_root = \"$url_main_root\";\n";
            $content .= "\$dolibarr_main_document_root = \"$doc_root\";\n";
            $content .= "\$dolibarr_main_document_root_alt = \"$doc_main_root_alt\";\n";

            File::append($filename, $content);
            return true;
        } else {
            \Log::error("Impossible d'ouvrir le fichier $filename.");
            return false;
        }
    }
}