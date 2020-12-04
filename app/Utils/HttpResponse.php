<?php

namespace App\Utils;

use Illuminate\Http\JsonResponse;

class HttpResponse
{
    private $httpCode = 200;

    private $httpData = [];

    private $httpMessage = "";

    private $httpHeader = [];

    public function setHttpCode(int $code): HttpResponse
    {
        $this->httpCode = $code;

        return $this;
    }

    public function setHttpData(array $data): HttpResponse
    {
        $this->httpData = $data;

        return $this;
    }

    public function setHttpMessage(string $message): HttpResponse
    {
        $this->httpMessage = $message;

        return $this;
    }

    public function setHttpHeader(array $header): HttpResponse
    {
        $this->httpHeader = $header;

        return $this;
    }

    public function jsonResponse(): JsonResponse
    {
        return response()->json([
            'message' => (string) $this->httpMessage,
            'code' => (int) $this->httpCode,
            'data' => (object) $this->httpData
        ], $this->httpCode, $this->httpHeader);
    }
}
