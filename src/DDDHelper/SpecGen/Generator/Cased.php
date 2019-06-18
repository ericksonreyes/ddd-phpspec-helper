<?php

namespace DDDHelper\SpecGen\Generator;

class Cased
{

    /**
     * @var string
     */
    private $string;

    /**
     * ToCamelCase constructor.
     * @param string $string
     */
    public function __construct(string $string)
    {
        $this->string = $string;
    }

    /**
     * @param $string
     * @return Cased
     */
    public static function make($string): self
    {
        return new self($string);
    }

    /**
     * @return string
     */
    public function asCamelCase(): string
    {
        $camelCasedString = '';
        $alphaNumericString = preg_replace('/[^A-Za-z0-9\\/ ]i/', ' ', $this->string);
        $arrayOfStrings = explode(' ', $alphaNumericString);

        foreach ($arrayOfStrings as $string) {
            $trimmedString = trim($string);
            if ($trimmedString !== '') {
                $camelCasedString .= ucfirst($trimmedString);
            }
        }

        return ucfirst($camelCasedString);
    }

    public function asSnakeCase(): string
    {
        $camelCasedStrings = [];
        $alphaNumericString = preg_replace('/[^A-Za-z0-9 ]/', ' ', $this->string);
        $arrayOfStrings = explode(' ', $alphaNumericString);

        foreach ($arrayOfStrings as $string) {
            $trimmedString = trim($string);
            if ($trimmedString !== '') {
                $camelCasedStrings[] = strtolower($trimmedString);
            }
        }

        return implode('_', $camelCasedStrings);
    }
}
