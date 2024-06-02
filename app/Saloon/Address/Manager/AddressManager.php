<?php

namespace App\Saloon\Address\Manager;

use App\Saloon\Address\AddressConnector;
use App\Saloon\Address\Requests\ViaCep;

class AddressManager
{
    public function getAddressByZipCode(string $zipCode): array
    {
        return app(AddressConnector::class)->send(new ViaCep($zipCode))->json();
    }
}
