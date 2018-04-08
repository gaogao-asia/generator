<?php
namespace LaravelRocket\Generator\Objects\OpenAPI;

class Request
{
    /** @var string */
    protected $controllerName;

    /** @var string */
    protected $method;

    /** @var string */
    protected $httpMethod;

    /** @var \TakaakiMizuno\SwaggerParser\Objects\Base */
    protected $info;

    /** @var \LaravelRocket\Generator\Objects\OpenAPI\PathElement[] */
    protected $elements;

    /** @var \LaravelRocket\Generator\Objects\OpenAPI\Action[] */
    protected $actions;

    /** @var \LaravelRocket\Generator\Objects\OpenAPI\OpenAPISpec */
    protected $spec;

    /** @var \LaravelRocket\Generator\Objects\OpenAPI\Definition */
    protected $response;

    /** @var string */
    protected $requestName;

    /** @var \LaravelRocket\Generator\Objects\OpenAPI\Parameter[] $parameters */
    protected $parameters = [];

    /**
     * Path constructor.
     *
     * @param string                                               $controllerName
     * @param string                                               $method
     * @param string                                               $httpMethod
     * @param \TakaakiMizuno\SwaggerParser\Objects\Base            $info
     * @param \LaravelRocket\Generator\Objects\OpenAPI\Definition  $response
     * @param \LaravelRocket\Generator\Objects\OpenAPI\OpenAPISpec $spec
     */
    public function __construct($controllerName, $method, $httpMethod, $info, $response, $spec)
    {
        $this->controllerName = $controllerName;
        $this->method         = $method;
        $this->httpMethod     = $httpMethod;
        $this->info           = $info;
        $this->spec           = $spec;
        $this->response       = $response;

        $this->setRequestName();
        $this->setParameters();
    }

    public function getName()
    {
        return $this->requestName;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setRequestName()
    {
        if ($this->response->getType() === Definition::TYPE_LIST) {
            $this->requestName = 'PaginationRequest';
        }

        $controllerRootName = str_replace('Controller', '', $this->controllerName);
        switch ($this->method) {
            case 'post':
            case 'put':
                $this->requestName = ucfirst($this->httpMethod).ucfirst($controllerRootName).'Request';
                break;
            default:
                $this->requestName = 'Request';
                break;
        }
    }

    public function setParameters()
    {
        $parameters = $this->info->parameters;
        foreach ($parameters as $parameter) {
            $this->parameters[] = new Parameter($parameter, $this->spec);
        }
    }
}
