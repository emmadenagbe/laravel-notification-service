<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class NotificationFlowTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_it_creates_and_processes_notification(): void
    {
        // Fake the queue to assert the job was pushed
        \Illuminate\Support\Facades\Queue::fake();

        $response = $this->postJson('/api/notifications', [
            'channels' => ['email'],
            'recipient' => 'test@example.com',
            'payload' => ['subject' => 'Hello', 'body' => 'World']
        ]);

        $response->assertStatus(202);

        $id = $response->json('id');

        $this->assertDatabaseHas('notifications', [
            'id' => $id,
            'status' => 'pending',
            'recipient' => 'test@example.com'
        ]);

        // Assert the job was pushed to the queue
        \Illuminate\Support\Facades\Queue::assertPushed(\Core\Infrastructure\Queue\Jobs\ProcessNotificationJob::class);
    }

    public function test_it_handles_notification_failure(): void
    {
        // We simulate the job failing. 
        // Since we can't easily trigger the "failed" callback in a simple synchronous test without mocking the queue worker logic deeply,
        // we will manually instantiate the job and call failed().

        $id = '00000000-0000-0000-0000-000000000000';

        // Create a notification manually
        $notification = \Core\Domain\Notification\Entity\Notification::create(
            id: $id,
            channels: [\Core\Domain\Notification\Enums\NotificationType::EMAIL],
            recipient: 'fail@example.com',
            payload: []
        );

        $repo = $this->app->make(\Core\Domain\Notification\Repository\NotificationRepositoryInterface::class);
        $repo->save($notification);

        // Instantiate job
        $job = new \Core\Infrastructure\Queue\Jobs\ProcessNotificationJob($id);

        // Call failed manually
        $job->failed(new \Exception("Something went wrong"));

        $this->assertDatabaseHas('notifications', [
            'id' => $id,
            'status' => 'failed',
            'error_message' => 'Something went wrong'
        ]);
    }
}
