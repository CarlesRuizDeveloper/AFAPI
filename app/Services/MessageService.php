<?php

namespace App\Services;

use App\Models\Message;

class MessageService
{
    public function getMessagesForChat($chatId)
    {
        return Message::where('chat_id', $chatId)->with('sender')->get();
    }

    public function sendMessage($chatId, $userId, $text)
    {
        return Message::create([
            'chat_id' => $chatId,
            'sender_id' => $userId,
            'message' => $text,
        ]);
    }
    

}
