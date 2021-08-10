<?php

namespace Tests\Unit\Token;

use App\Services\Token\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{

    public function testCreateStudentRole()
    {
        $role = Role::student();
        $this->assertInstanceOf(Role::class, $role);
        $this->assertTrue($role->getName() === 'student');
    }

    public function testCreateMentorRole()
    {
        $role = Role::mentor();
        $this->assertInstanceOf(Role::class, $role);
        $this->assertTrue($role->getName() === 'mentor');
    }
}
