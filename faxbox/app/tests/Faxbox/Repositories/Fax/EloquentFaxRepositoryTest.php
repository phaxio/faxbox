<?php namespace Faxbox\Repositories\Fax;

use Mockery;
use TestCase;
use PHPUnit_Framework_Assert as Assert;
class EloquentFaxRepositoryTest extends TestCase {

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @group tricks/repositories
     */
    public function testConstructor()
    {
        $faxMock = Mockery::mock('Faxbox\Fax');

        $faxRepository = new EloquentFaxRepository($faxMock);
        
        $this->assertSame(
            $faxMock,
            $this->readAttribute($faxRepository, 'model')
        );
    }
}
 