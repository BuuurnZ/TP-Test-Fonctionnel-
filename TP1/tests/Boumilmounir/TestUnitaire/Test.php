<?php

use PHPUnit\Framework\TestCase;
use src\Boumilmounir\TestUnitaire\TaskManager;

class TaskManagerTest extends TestCase
{
    private TaskManager $taskManager;

    protected function setUp(): void
    {
        $this->taskManager = new TaskManager();
    }

    public function testAddTask(): void
    {
        $this->taskManager->addTask("Tâche 1");
        $this->assertCount(1, $this->taskManager->getTasks());
        $this->assertEquals("Tâche 1", $this->taskManager->getTask(0));
    }

    public function testRemoveTask(): void
    {
        $this->taskManager->addTask("Tâche 1");
        $this->taskManager->removeTask(0);
        $this->assertCount(0, $this->taskManager->getTasks());
    }

    public function testGetTasks(): void
    {
        $this->taskManager->addTask("Tâche 1");
        $this->taskManager->addTask("Tâche 2");
        $this->assertEquals(["Tâche 1", "Tâche 2"], $this->taskManager->getTasks());
    }

    public function testGetTask(): void
    {
        $this->taskManager->addTask("Tâche 1");
        $this->assertEquals("Tâche 1", $this->taskManager->getTask(0));
    }

    public function testRemoveInvalidIndexThrowsException(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->taskManager->removeTask(0);
    }

    public function testGetInvalidIndexThrowsException(): void
    {
        $this->expectException(OutOfBoundsException::class);
        $this->taskManager->getTask(0);
    }

    public function testTaskOrderAfterRemoval(): void
    {
        $this->taskManager->addTask("Tâche 1");
        $this->taskManager->addTask("Tâche 2");
        $this->taskManager->addTask("Tâche 3");

        $this->taskManager->removeTask(1);

        $this->assertEquals(["Tâche 1", "Tâche 3"], $this->taskManager->getTasks());
    }
}
