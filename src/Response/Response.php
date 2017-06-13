<?php

namespace _404\Response;

/**
 * Handling a response.
 *
 */
class Response
{
    /**
    * Properties
    *
    */
    private $headers = [];  // Set all headers to send
    private $statusCode;    // Set statuscode to use
    private $body;          // Body to send with response



    /**
     * Set headers.
     *
     * @param string $header type of header to set
     *
     * @return self
     * @throws \Exception
     */
    public function setStatusCode($value)
    {
        $supportedValues = [200, 403, 404, 500];
        if (!in_array($value, $supportedValues)) {
            throw new \Exception("Unsupported statuscode: $value");
        }
        $this->statusCode = $value;

        return $this;
    }



    /**
     * Set headers.
     *
     * @param string $header type of header to set
     *
     * @return self
     */
    public function addHeader($header)
    {
        $this->headers[] = $header;

        return $this;
    }



    /**
     * Check if headers are already sent and throw exception if it is.
     *
     * @return void
     *
     * @throws \Exception
     */
    public function checkIfHeadersAlreadySent()
    {
        if (headers_sent($file, $line)) {
            throw new \Exception("Try to send headers but headers already sent, output started at $file line $line.");
        }
    }



    /**
     * Send headers.
     *
     * @return $this
     */
    public function sendHeaders()
    {
        $statusHeader = [
            "200" => "HTTP/1.1 200 OK",
            "403" => "HTTP/1.1 403 Forbidden",
            "404" => "HTTP/1.1 404 Not Found",
            "500" => "HTTP/1.1 500 Internal Server Error"
        ];

        $this->checkIfHeadersAlreadySent();

        if (isset($statusHeader[$this->statusCode])) {
            header($statusHeader[$this->statusCode]);
        }

        foreach ($this->headers as $header) {
            header($header);
        }

        return $this;
    }



    /**
     * Set the body.
     *
     * @param callable|string $body either a string or a callable that
     *                              can generate the body.
     *
     * @return self
     */
    public function setBody($body)
    {
        if (is_string($body)) {
            $this->body = $body;
        } elseif (is_callable($body)) {
            ob_start();
            $res1 = call_user_func($body);
            $res2 = ob_get_contents();
            $this->body = $res2 . $res1;
            ob_end_clean();
        }
        return $this;
    }

    /**
     * Set body to json from data and add content json to header.
     *
     * @param mixed data - data to be encoded to json
     *
     * @return self
     */
    public function setJson($data)
    {
        $this->addHeader("Content-Type: application/json; charset=utf8");
        $this->body = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        return $this;
    }

    /**
     * Get the body.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Send response with an optional statuscode.
     *
     * @param integer $statusCode optional statuscode to send.
     *
     * @return void
     */
    public function send($statusCode = null)
    {
        if ($statusCode) {
            $this->setStatusCode($statusCode);
        }

        if (!headers_sent()) {
            $this->sendHeaders();
        }
        echo $this->getBody();
    }



    /**
     * Send JSON response with an optional statuscode.
     *
     * @param mixed   $data       to be encoded as json.
     * @param integer $statusCode optional statuscode to send.
     *
     * @return void
     */
    public function sendJson($data, $statusCode = null)
    {
        if ($statusCode) {
            $this->setStatusCode($statusCode);
        }

        if (!headers_sent()) {
            $this->addHeader("Content-Type: application/json; charset=utf8");
            $this->sendHeaders();
        }
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Set header to redirect to url
     *
     * @param string $url to redirect to
     *
     * @return self
     */
    public function setRedirect($url)
    {
        $this->addHeader("Location: " . $url);

        return $this;
    }

    /**
     * Redirect to another page.
     *
     * @param string $url to redirect to
     *
     * @return void
     */
    public function redirect($url)
    {
        $this->checkIfHeadersAlreadySent();
        header("Location: " . $url);
    }
}
