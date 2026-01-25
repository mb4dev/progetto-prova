<?php

namespace core\exceptions;

use core\model\Response;
use Exception;
use TypeError;
use InvalidArgumentException;
use RuntimeException;
use PDOException;

class GlobalExceptionHandler
{
    /**
     * Handle any exception and return appropriate Response
     * 
     * @param Exception $e The exception to handle
     * @return Response The response object
     */
    public static function handle(Exception $e): Response
    {
        // Log the exception for debugging
        self::logException($e);

        // Handle specific exception types
        if ($e instanceof InvalidArgumentException) {
            return self::handleInvalidArgumentException($e);
        }

        if ($e instanceof TypeError) {
            return self::handleTypeError($e);
        }

        if ($e instanceof RuntimeException) {
            return self::handleRuntimeException($e);
        }

        if ($e instanceof PDOException) {
            return self::handleDatabaseException($e);
        }

        // Handle custom application exceptions
        if (strpos(get_class($e), 'app\\') === 0 || strpos(get_class($e), 'core\\') === 0) {
            return self::handleApplicationException($e);
        }

        // Default exception handling
        return self::handleGenericException($e);
    }

    /**
     * Handle InvalidArgumentException
     */
    private static function handleInvalidArgumentException(InvalidArgumentException $e): Response
    {
        return new Response(400, false, [
            'error' => 'Invalid argument provided',
            'message' => $e->getMessage(),
            'type' => 'InvalidArgumentException'
        ]);
    }

    /**
     * Handle TypeError
     */
    private static function handleTypeError(TypeError $e): Response
    {
        return new Response(400, false, [
            'error' => 'Type error occurred',
            'message' => 'Invalid data type provided',
            'type' => 'TypeError',
            'details' => $e->getMessage()
        ]);
    }

    /**
     * Handle RuntimeException
     */
    private static function handleRuntimeException(RuntimeException $e): Response
    {
        return new Response(500, false, [
            'error' => 'Runtime error occurred',
            'message' => $e->getMessage(),
            'type' => 'RuntimeException'
        ]);
    }

    /**
     * Handle PDOException (Database errors)
     */
    private static function handleDatabaseException(PDOException $e): Response
    {
        // Hide sensitive database information from client
        $message = 'Database operation failed';
        
        // In development, show more details
        if (self::isDevelopment()) {
            $message = $e->getMessage();
        }

        return new Response(500, false, [
            'error' => $message,
            'type' => self::getBaseClassName($e),
            'code' => $e->getCode()
        ]);
    }

    /**
     * Handle application-specific exceptions
     */
    private static function handleApplicationException(Exception $e): Response
    {
        // You can define specific application exception classes here
        // For now, handle generically
        return new Response(500, false, [
            'error' => 'Application error occurred',
            'message' => $e->getMessage(),
            'type' => self::getBaseClassName($e)
        ]);
    }

    /**
     * Handle generic exceptions
     */
    private static function handleGenericException(Exception $e): Response
    {
        $statusCode = 500;
        $message = 'An unexpected error occurred';

        // Check if it's an HTTP exception
        if (method_exists($e, 'getStatusCode')) {
            $statusCode = $e->getStatusCode();
        }

        // In development, show more details
        if (self::isDevelopment()) {
            $message = $e->getMessage();
        }

        return new Response($statusCode, false, [
            'error' => $message,
            'type' => class_basename($e),
            'code' => $e->getCode()
        ]);
    }

    /**
     * Log exception for debugging
     */
    private static function logException(Exception $e): void
    {
        $logMessage = sprintf(
            "[%s] %s: %s in %s on line %d\nStack trace:\n%s",
            date('Y-m-d H:i:s'),
            get_class($e),
            $e->getMessage(),
            $e->getFile(),
            $e->getLine(),
            $e->getTraceAsString()
        );

        // Log to error log
        error_log($logMessage);

        // You can also log to a file or external service
        // file_put_contents('/var/log/app_errors.log', $logMessage . "\n", FILE_APPEND);
    }

    /**
     * Check if application is in development mode
     */
    private static function isDevelopment(): bool
    {
        // Check environment variable or constant
        $env = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'production';
        return in_array(strtolower($env), ['development', 'dev', 'local']);
    }

    /**
     * Get the base class name without namespace
     */
    private static function getBaseClassName($class): string
    {
        $class = is_object($class) ? get_class($class) : $class;
        return basename(str_replace('\\', '/', $class));
    }

    /**
     * Handle validation errors (for ParameterValidator integration)
     */
    public static function handleValidationError(array $errors): Response
    {
        return new Response(400, false, [
            'error' => 'Validation failed',
            'validation_errors' => $errors,
            'type' => 'ValidationException'
        ]);
    }

    /**
     * Handle authentication errors
     */
    public static function handleAuthenticationError(string $message = 'Authentication failed'): Response
    {
        return new Response(401, false, [
            'error' => $message,
            'type' => 'AuthenticationException'
        ]);
    }

    /**
     * Handle authorization errors
     */
    public static function handleAuthorizationError(string $message = 'Access denied'): Response
    {
        return new Response(403, false, [
            'error' => $message,
            'type' => 'AuthorizationException'
        ]);
    }

    /**
     * Handle not found errors
     */
    public static function handleNotFoundError(string $message = 'Resource not found'): Response
    {
        return new Response(404, false, [
            'error' => $message,
            'type' => 'NotFoundException'
        ]);
    }

    /**
     * Handle method not allowed errors
     */
    public static function handleMethodNotAllowedError(array $allowedMethods = []): Response
    {
        return new Response(405, false, [
            'error' => 'Method not allowed',
            'allowed_methods' => $allowedMethods,
            'type' => 'MethodNotAllowedException'
        ]);
    }
}