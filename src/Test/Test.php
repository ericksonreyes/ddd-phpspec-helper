<?php
namespace Test;

use InvalidArgumentException;
use Vendor\Repository;

/**
 * Class Test
 * @package Test
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 * @SuppressWarnings(PHPMD.TooManyMethods)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 */
class Test
{
    /**
     * @var string
     */
    private $invoker;

    /**
	/* @var Vendor\Repository
	*/
	protected $repository;
	
	/**
	/* @var string
	*/
	protected $name;
	

    /**
     * Test constructor.
     *
     * @param string $invoker 
	 * @param Vendor\Repository $repository
	 * @param string $name
     * @throws \InvalidArgumentException
     */
    public function __construct(
        Identifier $invoker, 
		Vendor\Repository $repository, 
		string $name
    ) {
        $invoker = trim($invoker);
        if ($invoker === '') {
            throw new InvalidArgumentException('Test invoker is required.');
        }
        $this->invoker = $invoker; 
		$this->repository = $repository;
		$this->name = $name;
    }

    /**
    * @return string
    */
    public function invoker(): string
    {
        return $this->invoker;
    }

    
    /**
    * @return Vendor\Repository
    */
    public function repository(): Vendor\Repository
    {
        return $this->repository;
    }


    /**
    * @return string
    */
    public function name(): string
    {
        return $this->name;
    }

}
