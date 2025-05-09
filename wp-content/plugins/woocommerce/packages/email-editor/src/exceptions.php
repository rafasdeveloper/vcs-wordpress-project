<?php declare(strict_types = 1);

// phpcs:ignoreFile PSR1.Classes.ClassDeclaration
<<<<<<< HEAD
namespace Automattic\WooCommerce\EmailEditor;
=======
namespace MailPoet\EmailEditor;
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)

/**
 * Provides information for converting exceptions to HTTP responses.
 */
interface HttpAwareException {
  public function getHttpStatusCode(): int;
}


/**
<<<<<<< HEAD
 * Frames all exceptions ("$e instanceof Automattic\WooCommerce\EmailEditor\Exception").
=======
 * Frames all exceptions ("$e instanceof MailPoet\EmailEditor\Exception").
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
 */
abstract class Exception extends \Exception {
  /** @var string[] */
  private $errors = [];

<<<<<<< HEAD
  final public function __construct(string $message = '', int $code = 0, ?\Throwable $previous = null) {
=======
  final public function __construct(string $message = '', int $code = 0, \Throwable $previous = null) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
    parent::__construct($message, $code, $previous);
  }

  /** @return static */
<<<<<<< HEAD
  public static function create(?\Throwable $previous = null) {
=======
  public static function create(\Throwable $previous = null) {
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
    return new static('', 0, $previous);
  }

  /** @return static */
  public function withMessage(string $message) {
    $this->message = $message;
    return $this;
  }

  /** @return static */
  public function withCode(int $code) {
    $this->code = $code;
    return $this;
  }

<<<<<<< HEAD
  /**
   * @param string[] $errors
   * @return static
   */
=======
  /** @return static */
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
  public function withErrors(array $errors) {
    $this->errors = $errors;
    return $this;
  }

  /** @return static */
  public function withError(string $id, string $error) {
    $this->errors[$id] = $error;
    return $this;
  }

<<<<<<< HEAD
  /**
   * @return string[]
   */
=======
>>>>>>> b1eea7a (Merged existing code from https://dev-vices.rafaeldeveloper.co)
  public function getErrors(): array {
    return $this->errors;
  }
}


/**
 * USE: Generic runtime error. When possible, use a more specific exception instead.
 * API: 500 Server Error (not HTTP-aware)
 */
class RuntimeException extends Exception {}


/**
 * USE: When wrong data VALUE is received.
 * API: 400 Bad Request
 */
class UnexpectedValueException extends RuntimeException implements HttpAwareException {
  public function getHttpStatusCode(): int {
    return 400;
  }
}


/**
 * USE: When an action is forbidden for given actor (although generally valid).
 * API: 403 Forbidden
 */
class AccessDeniedException extends UnexpectedValueException implements HttpAwareException {
  public function getHttpStatusCode(): int {
    return 403;
  }
}


/**
 * USE: When the main resource we're interested in doesn't exist.
 * API: 404 Not Found
 */
class NotFoundException extends UnexpectedValueException implements HttpAwareException {
  public function getHttpStatusCode(): int {
    return 404;
  }
}


/**
 * USE: When the main action produces conflict (i.e. duplicate key).
 * API: 409 Conflict
 */
class ConflictException extends UnexpectedValueException implements HttpAwareException {
  public function getHttpStatusCode(): int {
    return 409;
  }
}


/**
 * USE: An application state that should not occur. Can be subclassed for feature-specific exceptions.
 * API: 500 Server Error (not HTTP-aware)
 */
class InvalidStateException extends RuntimeException {}

class NewsletterProcessingException extends Exception {}
