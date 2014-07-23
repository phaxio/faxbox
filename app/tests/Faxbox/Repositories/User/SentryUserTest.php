<?php namespace Faxbox\Repositories\User;

use Mockery;
use TestCase;

class SentryUserTest extends TestCase {

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @group tricks/repositories
     */
    public function testConstructor()
    {
        $sentryMock = Mockery::mock('Cartalyst\Sentry\Sentry')->makePartial();

        $userRepository = new SentryUser($sentryMock);

        $this->assertSame(
            $sentryMock,
            $this->readAttribute($userRepository, 'sentry')
        );
    }
    
    public function testStore()
    {

        $data = ['email' => 'foo', 'password' => 'bar'];
        
        $returnedUser = Mockery::mock('Cartalyst\Sentry\Users\UserInterface')->makePartial();

        $returnedUser
            ->shouldReceive('getActivationCode')
            ->once()
            ->andReturn('code');

        $returnedUser
            ->shouldReceive('getId')
            ->once()
            ->andReturn(1);
        
        $sentryMock = \Sentry::shouldReceive('register')
            ->once()
            ->with($data, true)
            ->andReturn($returnedUser)
            ->getMock();

        $userRepository = new SentryUser($sentryMock);

        $expectedResult = [
            'success' => true,
            'message' => trans('users.created'),
            'mailData' => [
                'activationCode' => 'code',
                'userId' => 1,
                'email' => $data['email']
            ]
        ];
        
        $this->assertEquals(
            $expectedResult,
            $userRepository->store($data)
        );
    }
    
    public function testUpdateByAdmin()
    {
        $this->testUpdateByNonAdmin(true);
    }
    
    public function testUpdateByNonAdmin($isAdmin = false)
    {
        $data = [
            'id' => 1, 
            'email' => 'foo',
            'firstName' => 'bob',
            'lastName' => 'dude',
            'groups' => ['mod']
        ];

        $returnedUser = Mockery::mock('Cartalyst\Sentry\Users\UserInterface')->makePartial();
        
        $returnedUser->first_name = '';
        $returnedUser->last_name = '';

        $sentryMock = \Sentry::shouldReceive('findUserById')
            ->once()
            ->andReturn($returnedUser)
            ->getMock();
        
        $sentryMock
            ->shouldReceive('getUser')
            ->once()
            ->andReturn($returnedUser);

        $returnedUser
            ->shouldReceive('hasAccess')
            ->once()
            ->andReturn($isAdmin);
        
        $returnedUser
            ->shouldReceive('save')
            ->once()
            ->andReturn(true);

        if($isAdmin)
        {
            $group = new \stdClass();
            $group->id = 'mod';
            
            $groups = [ $group ];
            
            $groupMock = Mockery::mock('Cartalyst\Sentry\Groups\GroupInterface')->makePartial();
            
            $sentryMock
                ->shouldReceive('getGroupProvider')
                ->once()
                ->andReturn($groupMock);
            
            $groupMock
                ->shouldReceive('findAll')
                ->once()
                ->andReturn($groupMock);
        }
        
        
        $userRepository = new SentryUser($sentryMock);
                
        $expectedResult = [
            'success' => true,
            'message' => trans('users.updated')
        ];

        $this->assertEquals(
            $expectedResult,
            $userRepository->update($data)
        );

        $this->assertEquals(
            'bob',
            $returnedUser->first_name
        );
    }
}
 