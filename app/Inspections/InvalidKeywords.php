<?php

namespace App\Inspections;

use Exception;

class InvalidKeywords
{
    /**
     * All registered invalid keywords.
     *
     * @var array
     */
    protected $keywords = [
        '<?php',
        '<?',
        '?>',
    ];

    /**
     * Detect spam.
     *
     * @param string $body
     *
     * @throws \Exception
     */
    public function detect($body)
    {
        foreach ($this->keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw new Exception('Sua mensagem contém caracteres inválidos.');
            }
        }
    }
}
