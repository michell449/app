<?php

namespace CfdiUtils\SumasPagos20;

use JsonSerializable;

final class Pago implements JsonSerializable
{
    /** @var Decimal */
    private $monto;

    /** @var Decimal */
    private $montoMinimo;

    /** @var Decimal */
    private $tipoCambioP;

    /** @var Impuestos */
    private $impuestos;

    public function __construct(Decimal $monto, Decimal $montoMinimo, Decimal $tipoCambioP, Impuestos $impuestos)
    {
        $this->monto = $monto;
        $this->montoMinimo = $montoMinimo;
        $this->tipoCambioP = $tipoCambioP;
        $this->impuestos = $impuestos;
    }

    public function getMonto(): Decimal
    {
        return $this->monto;
    }

    public function getMontoMinimo(): Decimal
    {
        return $this->montoMinimo;
    }

    public function getTipoCambioP(): Decimal
    {
        return $this->tipoCambioP;
    }

    public function getImpuestos(): Impuestos
    {
        return $this->impuestos;
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return [
            'monto' => $this->monto,
            'montoMinimo' => $this->montoMinimo,
            'tipoCambioP' => $this->tipoCambioP,
            'impuestos' => $this->impuestos,
        ];
    }
}
