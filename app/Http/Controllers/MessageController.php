<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    public function index($chatId)
    {
        $messages = $this->messageService->getMessagesForChat($chatId);
        return response()->json($messages, 200);
    }

    public function sendMessage(Request $request, $chatId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);
    
        $message = $this->messageService->sendMessage($chatId, auth()->id(), $request->message);
        return response()->json($message, 201);
    }    
}
