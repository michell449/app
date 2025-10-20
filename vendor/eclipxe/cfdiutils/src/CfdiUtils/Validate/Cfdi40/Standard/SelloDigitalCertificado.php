<?php

namespace CfdiUtils\Validate\Cfdi40\Standard;

use CfdiUtils\Validate\Cfdi40\Abstracts\AbstractDiscoverableVersion40;
use CfdiUtils\Validate\Common\SelloDigitalCertificadoValidatorTrait;
use CfdiUtils\Validate\Contracts\RequireXmlResolverInterface;
use CfdiUtils\Validate\Contracts\RequireXmlStringInterface;
use CfdiUtils\Validate\Contracts\RequireXsltBuilderInterface;
use CfdiUtils\Validate\Status;

/**
 * SelloDigitalCertificado
 *
 * Valida que:
 * - SELLO01: Se puede obtener el certificado del comprobante
 * - SELLO02: El número de certificado del comprobante igual al encontrado en el certificado
 * - SELLO03: El RFC del comprobante igual al encontrado en el certificado
 * - SELLO04: El nombre del emisor del comprobante es igual al encontrado en el certificado
 * - SELLO05: La fecha del documento es mayor o igual a la fecha de inicio de vigencia del certificado
 * - SELLO06: La fecha del documento menor o igual a la fecha de fin de vigencia del certificado
 * - SELLO07: El sello del comprobante está en base 64
 * - SELLO08: El sello del comprobante coincide con el certificado y la cadena de origen generada
 */
class SelloDigitalCertificado extends AbstractDiscoverableVersion40 implements
    RequireXmlStringInterface,
    RequireXmlResolverInterface,
    RequireXsltBuilderInterface
{
    use SelloDigitalCertificadoValidatorTrait;

    protected function validateNombre(string $emisorNombre, string $rfc)
    {
        if ('' === $emisorNombre) {
            $this->asserts->putStatus('SELLO04', Status::error(), 'Nombre del emisor vacío');
            return;
        }

        // Remove régimen de capital from name when is "Persona Moral" only.
        $removeSuffixFromName = 12 === mb_strlen($rfc);

        $this->asserts->putStatus(
            'SELLO04',
            Status::when($this->certificado->getName($removeSuffixFromName) === $emisorNombre),
            sprintf('Nombre certificado: %s, Nombre comprobante: %s.', $this->certificado->getName(), $emisorNombre)
        );
    }
}
