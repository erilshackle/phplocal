<?php

namespace core\base;

final class Request
{
    protected $method;
    protected $uri;
    protected $queryParams;
    protected $bodyParams;
    protected $headers;
    protected $hostname;
    protected $protocol;

    /**
     * Construtor da classe Request.
     * Inicializa os dados da requisição.
     */
    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $this->queryParams = $_GET;
        $this->bodyParams = $_POST;
        $this->headers = $this->setHeaders();
        $this->hostname = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'];
        $this->protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    }

    /**
     * Retorna o método HTTP da requisição.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Retorna o caminho da URI requisitada.
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Retorna um parâmetro da query string.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getQueryParam($key, $default = null)
    {
        return $this->queryParams[$key] ?? $default;
    }

    /**
     * Retorna todos os parâmetros da query string.
     *
     * @return array
     */
    public function getAllQueryParams()
    {
        return $this->queryParams;
    }

    /**
     * Retorna um parâmetro do corpo da requisição.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getBodyParam($key, $default = null)
    {
        return $this->bodyParams[$key] ?? $default;
    }

    /**
     * Retorna todos os parâmetros do corpo da requisição.
     *
     * @return array
     */
    public function getAllBodyParams()
    {
        return $this->bodyParams;
    }

    /**
     * Retorna um parâmetro do $_GET sanetizado.
     *
     * @param string $key
     * @param int $filter
     * @param mixed $default
     * @return mixed
     */
    public function getParam(string $key, int $filter = FILTER_SANITIZE_SPECIAL_CHARS, $default = null)
    {
        if (isset($this->queryParams[$key])) {
            return filter_var($this->queryParams[$key], $filter);
        }
        return $default;
    }

    /**
     * Retorna um parâmetro do $_POST sanetizado.
     *
     * @param string $key
     * @param int $filter
     * @param mixed $default
     * @return mixed
     */
    public function postParam(string $key, int $filter = FILTER_SANITIZE_SPECIAL_CHARS, $default = null)
    {
        if (isset($this->bodyParams[$key])) {
            return filter_var($this->bodyParams[$key], $filter);
        }
        return $default;
    }

    /**
     * Retorna o valor de um cabeçalho específico.
     *
     * @param string $key
     * @return string|null
     */
    public function getHeader($key)
    {
        return $this->headers[$key] ?? null;
    }

    /**
     * Retorna todos os cabeçalhos da requisição.
     *
     * @return array
     */
    public function getAllHeaders()
    {
        return $this->headers;
    }

    private function setHeaders() {
        if (function_exists('getallheaders')) {
            return getallheaders();
        } else {
            $headers = [];
            foreach ($_SERVER as $name => $value) {
                if (substr($name, 0, 5) == 'HTTP_') {
                    $headerName = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $headers[$headerName] = $value;
                }
            }
            return $headers;
        }
    }

    /**
     * Retorna o host (hostname) da requisição.
     *
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * Retorna o protocolo (http ou https).
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->protocol;
    }

    /**
     * Retorna a URL base da requisição (protocolo + host).
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->protocol . '://' . $this->hostname;
    }

    /**
     * Retorna o endereço IP do cliente.
     *
     * @return string|null
     */
    public function getClientIp()
    {
        return $_SERVER['REMOTE_ADDR'] ?? null;
    }

    /**
     * Retorna a URL completa (base URL + URI).
     *
     * @return string
     */
    public function getFullUrl()
    {
        return $this->getBaseUrl() . $this->getUri();
    }

    /**
     * Retorna o referenciador (página anterior).
     *
     * @return string|null
     */
    public function getReferer()
    {
        return $_SERVER['HTTP_REFERER'] ?? null;
    }

    /**
     * Retorna o User-Agent (informações sobre o navegador do cliente).
     *
     * @return string|null
     */
    public function getUserAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? null;
    }

    /**
     * Verifica se a requisição foi feita via AJAX.
     *
     * @return bool
     */
    public function isAjax()
    {
        return strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest';
    }

    /**
     * Verifica se a requisição foi feita via HTTPS.
     *
     * @return bool
     */
    public function isSecure()
    {
        return $this->protocol === 'https';
    }

    /**
     * Verifica se o método da requisição é POST.
     *
     * @return bool
     */
    public function isPost()
    {
        return $this->method === 'POST';
    }

    /**
     * Verifica se o método da requisição é GET.
     *
     * @return bool
     */
    public function isGet()
    {
        return $this->method === 'GET';
    }

    /**
     * Verifica se o método da requisição é PUT.
     *
     * @return bool
     */
    public function isPut()
    {
        return $this->method === 'PUT';
    }

    /**
     * Verifica se o método da requisição é DELETE.
     *
     * @return bool
     */
    public function isDelete()
    {
        return $this->method === 'DELETE';
    }

    /**
     * Verifica se a requisição é multipart (usada para uploads de arquivos).
     *
     * @return bool
     */
    public function isMultipart()
    {
        return stripos($this->getHeader('Content-Type'), 'multipart/form-data') !== false;
    }

    /**
     * Retorna os arquivos enviados via POST (para uploads).
     *
     * @return array
     */
    public function getFiles()
    {
        return $_FILES;
    }
}
