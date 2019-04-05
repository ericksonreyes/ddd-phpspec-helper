<?php
namespace spec\Test;

use EricksonReyes\DomainDrivenDesign\Domain\ValueObject\Identifier;
use Test\Hey;
use Faker\Factory;
use Faker\Generator;
use PhpSpec\ObjectBehavior;
use InvalidArgumentException;

class HeySpec extends ObjectBehavior
{
    /**
     * @var Generator
     */
    protected $seeder;

    /**
    * @var Identifier
    */
    protected $expectedInvoker;

    /**
	/* @var string
	*/
	protected $expectedSs;
	
	/**
	/* @var int
	*/
	protected $expectedIi;
	
	/**
	/* @var float
	*/
	protected $expectedFf;
	
	/**
	/* @var array
	*/
	protected $expectedAa;
	
	/**
	/* @var bool
	*/
	protected $expectedBb;
	

    public function __construct()
    {
        $this->seeder = Factory::create();
    }

    public function let(Identifier $invoker)
    {
        $invoker->isEmpty()->shouldBeCalled()->willReturn(false);
        $this->beConstructedWith(
            $this->expectedInvoker = $invoker, 
			$this->expectedSs = $this->seeder->word, 
			$this->expectedIi = $this->seeder->numberBetween(1, 100000), 
			$this->expectedFf = $this->seeder->randomFloat(2, 1, 100000), 
			$this->expectedAa = $this->seeder->paragraphs, 
			$this->expectedBb = $this->seeder->boolean
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Hey::class);
    }

    public function it_requires_a_command_invoker(Identifier $invoker)
    {
        $invoker->isEmpty()->shouldBeCalled()->willReturn(true);
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [
            $invoker, 
			$this->expectedSs = $this->seeder->word, 
			$this->expectedIi = $this->seeder->numberBetween(1, 100000), 
			$this->expectedFf = $this->seeder->randomFloat(2, 1, 100000), 
			$this->expectedAa = $this->seeder->paragraphs, 
			$this->expectedBb = $this->seeder->boolean
        ]);
    }

    public function it_has_a_command_invoker()
    {
        $this->invoker()->shouldReturn($this->expectedInvoker);
    }

    
    public function it_has_ss()
    {
        return $this->ss()->shouldReturn($this->expectedSs);
    }


    public function it_has_ii()
    {
        return $this->ii()->shouldReturn($this->expectedIi);
    }


    public function it_has_ff()
    {
        return $this->ff()->shouldReturn($this->expectedFf);
    }


    public function it_has_aa()
    {
        return $this->aa()->shouldReturn($this->expectedAa);
    }


    public function it_has_bb()
    {
        return $this->bb()->shouldReturn($this->expectedBb);
    }

}