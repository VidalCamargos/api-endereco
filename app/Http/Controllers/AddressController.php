<?php

namespace App\Http\Controllers;

use App\Saloon\Address\Manager\AddressManager;
use Illuminate\Http\JsonResponse;
use Throwable;

class AddressController
{
    public function __construct(private readonly AddressManager $addressManager)
    {
    }

    public function getAddress(): JsonResponse
    {
        try {
            return response()->json($this->addressManager->getAddressByZipCode('32671412'));
        } catch (Throwable $exception) {
            dd($exception->getMessage());
            return response()->json('Ocorreu um erro ao tentar buscar pelo endereço.', $exception->getCode());
        }
    }
}
