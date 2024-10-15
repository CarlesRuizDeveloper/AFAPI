<?php

namespace App\Http\Controllers;

use App\Services\ChatService;
use App\Services\MessageService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $chatService;
    protected $messageService;

    public function __construct(ChatService $chatService, MessageService $messageService)
    {
        $this->chatService = $chatService;
        $this->messageService = $messageService;
    }

    // Ruta para obtener mensajes
    public function getMessages($chat_id)
    {
        try {
            $messages = $this->messageService->getMessagesForChat($chat_id);
            return response()->json($messages, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener mensajes: ' . $e->getMessage()], 500);
        }
    }

    // Ruta para enviar mensaje
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'sender_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        try {
            $message = $this->messageService->sendMessage($request->chat_id, $request->sender_id, $request->message);
            return response()->json($message, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al enviar el mensaje: ' . $e->getMessage()], 500);
        }
    }

    public function createChat(Request $request)
    {
        try {
            $authenticatedUserId = auth()->id();
            if (!$authenticatedUserId) {
                return response()->json(['error' => 'No se pudo obtener el ID del usuario autenticado.'], 401);
            }
    
            // El ID del usuario autenticado debe venir de auth()->id()
            $chat = $this->chatService->createChat(
                $authenticatedUserId,   // Este es el usuario autenticado (user_1_id)
                $request->user_2_id, 
                $request->llibre_id
            );
            
            return response()->json($chat, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear el chat: ' . $e->getMessage()], 500);
        }
    }
    
    
    
    
}
