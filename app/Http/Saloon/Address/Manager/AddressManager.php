<?php

namespace App\Http\Saloon\Address\Manager;

use App\Http\Saloon\Address\AddressConnector;
use App\Http\Saloon\Address\Requests\ViaCep;

class AddressManager
{
    public function getAddressByZipCode(string $zipCode): array
    {
        return app(AddressConnector::class)->send(new ViaCep($zipCode))->json();
    }
}
