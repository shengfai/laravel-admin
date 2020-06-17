<?php

namespace Shengfai\LaravelAdmin\Exceptions;

use Shengfai\LaravelAdmin\Contracts\ErrorCodes;

/**
 * 校验异常类
 * Class ValidationFailedException
 *
 * @package App\Exceptions
 * @author CocaCoffee <CocaCoffee@vip.qq.com>
 */
class ValidationFailedException extends \LogicException
{

    /**
     * ValidationFailedException constructor.
     *
     * @param string $message
     * @param int $code
     */
    public function __construct(int $code = null, string $message = null, \Throwable $previous = null)
    {
        $code = $code ?? ErrorCodes::HTTP_UNPROCESSABLE_ENTITY;
        $message = $message ?? trans('Validation failed for one or more entities.');
        parent::__construct($message, $code, $previous);
    }
}