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

    public function setHttpData($data): HttpResponse
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
            'code' => (int) $this->httpCode,
            'message' => (string) $this->httpMessage,
            'data' => (object) $this->httpData
        ], $this->httpCode, $this->httpHeader);
    }

    public function streamResponse()
    {
        return response($this->httpData, (int) $this->httpCode, $this->httpHeader);
    }
}
