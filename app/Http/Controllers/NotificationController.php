<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store(\App\Http\Requests\CreateNotificationApiRequest $request, \Core\Application\UseCases\CreateNotificationUseCase $useCase)
    {
        $validated = $request->validated();

        $dto = new \Core\Application\DTOs\CreateNotificationRequest(
            channels: $validated['channels'],
            recipient: $validated['recipient'],
            payload: $validated['payload']
        );

        $id = $useCase->execute($dto);

        return response()->json([
            'message' => 'Notification accepted',
            'id' => $id,
            'status' => 'pending'
        ], 202);
    }
}
