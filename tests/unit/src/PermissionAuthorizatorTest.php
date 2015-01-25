<?php

namespace Tests\Unit;

use Arachne\Security\FirewallInterface;
use Arachne\Security\Permission;
use Arachne\Security\PermissionAuthorizator;
use Codeception\TestCase\Test;
use Mockery;
use Mockery\MockInterface;
use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
class PermissionAuthorizatorTest extends Test
{

	/** @var MockInterface */
	private $firewall;

	/** @var MockInterface */
	private $permission;

	/** @var PermissionAuthorizator */
	private $authorizator;

	protected function _before()
	{
		$this->firewall = Mockery::mock(FirewallInterface::class);
		$this->permission = Mockery::mock(Permission::class);
		$this->authorizator = new PermissionAuthorizator($this->firewall, $this->permission);
	}

	public function testRoles()
	{
		$identity = Mockery::mock(IIdentity::class);
		$identity
			->shouldReceive('getRoles')
			->once()
			->andReturn([
				'role1',
				'role2',
				'role3',
			]);

		$this->firewall
			->shouldReceive('getIdentity')
			->once()
			->andReturn($identity);

		$this->permission
			->shouldReceive('setIdentity')
			->once()
			->with($identity);

		$this->permission
			->shouldReceive('isAllowed')
			->once()
			->with('role1', 'resource', 'privilege')
			->andReturn(FALSE);

		$this->permission
			->shouldReceive('isAllowed')
			->once()
			->with('role2', 'resource', 'privilege')
			->andReturn(TRUE);

		$this->assertTrue($this->authorizator->isAllowed('resource', 'privilege'));
	}

	public function testGuestRole()
	{
		$this->firewall
			->shouldReceive('getIdentity')
			->once()
			->andReturn(NULL);

		$this->permission
			->shouldReceive('setIdentity')
			->once()
			->with(NULL);

		$this->permission
			->shouldReceive('isAllowed')
			->once()
			->with('my_guest', 'resource', 'privilege')
			->andReturn(FALSE);

		$this->authorizator->guestRole = 'my_guest';

		$this->assertFalse($this->authorizator->isAllowed('resource', 'privilege'));
	}

}