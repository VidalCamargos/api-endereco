<?php

namespace App\Http\Controllers;

use App\Http\Saloon\Address\Manager\AddressManager;
use Illuminate\Http\JsonResponse;
use Throwable;

class AddressController
{
    public function __construct(private readonly AddressManager $addressManager)
    {
    }

    public function getAddress(string $zipCode): JsonResponse
    {
        try {
            return response()->json($this->addressManager->getAddressByZipCode($zipCode));
        } catch (Throwable $exception) {
            return response()->json('Ocorreu um erro ao tentar buscar pelo endereço.', $exception->getCode());
        }
    }
}
