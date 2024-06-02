<?php

namespace App\Http\Saloon\Address\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class ViaCep extends Request
{
    protected Method $method = Method::GET;

    public function __construct(private readonly string $zipCode)
    {
    }

    public function resolveEndpoint(): string
    {
        return "https://viacep.com.br/ws/$this->zipCode/json/";
    }
}
