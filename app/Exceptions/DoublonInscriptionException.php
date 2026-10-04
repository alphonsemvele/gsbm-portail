<?php

namespace App\Exceptions;

use App\Models\Eleve;
use RuntimeException;

/** Levee quand une inscription creerait un doublon. */
class DoublonInscriptionException extends RuntimeException
{
    public function __construct(string $message, public readonly ?Eleve $eleve = null)
    {
        parent::__construct($message);
    }
}
