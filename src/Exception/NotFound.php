<?php declare(strict_types=1);

namespace Circli\Database\Exception;

use Circli\Database\Values\GenericId;
use DomainException;
use Throwable;

class NotFound extends DomainException
{
	public static function generic(GenericId $id): static
	{
		return new static(sprintf('Record with id "%s" not found', $id->toString()));
	}

	final public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
	{
		parent::__construct($message, $code, $previous);
	}
}
