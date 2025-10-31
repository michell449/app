<?php
// C:\xampp\htdocs\app\core\autoload-phpcfdi.php

spl_autoload_register(function ($class) {
    $prefixes = [
        'PhpCfdi\\SatWsDescargaMasiva\\' => __DIR__ . '/../vendor/phpcfdi/sat-ws-descarga-masiva/src/',
        'PhpCfdi\\CfdiCleaner\\'          => __DIR__ . '/../vendor/phpcfdi/cfdi-cleaner/src/',
        'PhpCfdi\\CfdiToPdf\\'            => __DIR__ . '/../vendor/phpcfdi/cfditopdf/src/',
        'PhpCfdi\\Credentials\\'          => __DIR__ . '/../vendor/phpcfdi/credentials/src/',
        'PhpCfdi\\Rfc\\'                  => __DIR__ . '/../vendor/phpcfdi/rfc/src/',
        'PhpCfdi\\XmlCancelacion\\'       => __DIR__ . '/../vendor/phpcfdi/xml-cancelacion/src/',
        'PhpCfdi\\XmlResources\\'         => __DIR__ . '/../vendor/phpcfdi/xml-resources/src/',
        'PhpCfdi\\XmlSchemas\\'           => __DIR__ . '/../vendor/phpcfdi/xml-schemas/src/',
        'PhpCfdi\\XmlUtils\\'             => __DIR__ . '/../vendor/phpcfdi/xml-utils/src/',
        'Eclipxe\\Enum\\'                 => __DIR__ . '/../vendor/eclipxe/enum/src/',
        'Eclipxe\\CfdiUtils\\'            => __DIR__ . '/../vendor/eclipxe/cfdiutils/src/',
        'GuzzleHttp\\'                    => __DIR__ . '/../vendor/guzzlehttp/guzzle/src/',
        'Mpdf\\'                         => __DIR__ . '/../vendor/mpdf/mpdf/src/',
        'Psr\\Http\\Message\\'           => __DIR__ . '/../vendor/psr/http-message/src/',
        'Psr\\Http\\Client\\'            => __DIR__ . '/../vendor/psr/http-client/src/',
        'GuzzleHttp\\Psr7\\'              => __DIR__ . '/../vendor/guzzlehttp/psr7/src/',
        'GuzzleHttp\\Promise\\'          => __DIR__ . '/../vendor/guzzlehttp/promises/src/',
        'Eclipxe\\MicroCatalog\\'         => __DIR__ . '/../vendor/eclipxe/micro-catalog/src/',
        'PHPMailer\\PHPMailer\\'           => __DIR__ . '/../vendor/phpmailer/phpmailer/src/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) !== 0) {
            continue;
        }
        $relativeClass = substr($class, $len);
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
