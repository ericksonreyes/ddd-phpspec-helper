<?php
namespace Test;

use EricksonReyes\DomainDrivenDesign\Domain\ValueObject\Identifier;
use InvalidArgumentException;

/**
 * Class Hey
 * @package Test
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Hey
{
    /**
     * @var Identifier
     */
    private $invoker;

    /**
	/* @var string
	*/
	protected $ss;
	
	/**
	/* @var int
	*/
	protected $ii;
	
	/**
	/* @var float
	*/
	protected $ff;
	
	/**
	/* @var array
	*/
	protected $aa;
	
	/**
	/* @var bool
	*/
	protected $bb;
	

    /**
     * Hey constructor.
     *
     * @param Identifier $invoker 
	 * @param string $ss
	 * @param int $ii
	 * @param float $ff
	 * @param array $aa
	 * @param bool $bb
     * @throws \InvalidArgumentException
     */
    public function __construct(
        Identifier $invoker, 
		string $ss, 
		int $ii, 
		float $ff, 
		array $aa, 
		bool $bb
    ) {
        if ($invoker->isEmpty()) {
            throw new InvalidArgumentException('Hey invoker is required.');
        }
        $this->invoker = $invoker; 
		$this->ss = $ss;
		$this->ii = $ii;
		$this->ff = $ff;
		$this->aa = $aa;
		$this->bb = $bb;
    }

    /**
    * @return Identifier
    */
    public function invoker(): Identifier
    {
        return $this->invoker;
    }

    
    /**
    * @return string
    */
    public function ss(): string
    {
        return $this->ss;
    }


    /**
    * @return int
    */
    public function ii(): int
    {
        return $this->ii;
    }


    /**
    * @return float
    */
    public function ff(): float
    {
        return $this->ff;
    }


    /**
    * @return array
    */
    public function aa(): array
    {
        return $this->aa;
    }


    /**
    * @return bool
    */
    public function bb(): bool
    {
        return $this->bb;
    }

}
