<?php

namespace App\Services;

use App\Models\Chat;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ChatService
{
    public function getChatsForUser($userId)
    {
        return Chat::where('user_1_id', $userId)
            ->orWhere('user_2_id', $userId)
            ->with(['user1:id,name', 'user2:id,name', 'llibre:id,titol,curs'])
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function createChat($user1Id, $user2Id, $llibreId)
    {
        if (is_null($user1Id) || is_null($user2Id) || is_null($llibreId)) {
            throw new \Exception('Los valores de user1Id, user2Id o llibreId no pueden ser null');
        }

        try {
            // Asegurarse de que los IDs no sean nulos y se estén pasando correctamente
            $chat = Chat::create([
                'user_1_id' => $user1Id,   // Usuario autenticado
                'user_2_id' => $user2Id,   // Usuario con el que se desea chatear
                'llibre_id' => $llibreId,  // ID del libro
            ]);

            return $chat;
        } catch (\Exception $e) {
            throw new \Exception('Error al crear el chat: ' . $e->getMessage());
        }
    }




    public function getChatById($chatId, $userId)
    {
        $chat = Chat::with(['user1', 'user2', 'llibre'])
            ->where('id', $chatId)
            ->where(function ($query) use ($userId) {
                $query->where('user_1_id', $userId)
                    ->orWhere('user_2_id', $userId);
            })
            ->first();

        if (!$chat) {
            throw new ModelNotFoundException('Chat no encontrado');
        }

        return $chat;
    }

    // Nuevo método para buscar un chat existente
    public function findExistingChat($user1Id, $user2Id, $llibreId)
    {
        return Chat::where(function ($query) use ($user1Id, $user2Id) {
            $query->where('user_1_id', $user1Id)
                ->where('user_2_id', $user2Id);
        })
            ->orWhere(function ($query) use ($user1Id, $user2Id) {
                $query->where('user_1_id', $user2Id)
                    ->where('user_2_id', $user1Id);
            })
            ->where('llibre_id', $llibreId)
            ->first();
    }

    public function getUnreadChatsCount($userId)
    {
        return Chat::where(function ($query) use ($userId) {
            $query->where('user_1_id', $userId)
                ->orWhere('user_2_id', $userId);
        })
            ->whereHas('messages', function ($query) use ($userId) {
                $query->where('read', false)  // Campo `read` para determinar si el mensaje ha sido leído.
                    ->where('sender_id', '!=', $userId); // Filtrar los mensajes recibidos, no los enviados por el propio usuario
            })
            ->count();
    }

    public function getChatsWithMessages($userId)
    {
        return Chat::where(function ($query) use ($userId) {
            $query->where('user_1_id', $userId)
                ->orWhere('user_2_id', $userId);
        })
            ->whereHas('messages') // Este filtro asegura que sólo se obtendrán los chats que tienen al menos un mensaje
            ->with(['user1', 'user2', 'llibre', 'messages'])
            ->get();
    }

    public function countChatsWithMessages($userId)
    {
        return Chat::where(function ($query) use ($userId) {
            $query->where('user_1_id', $userId)
                ->orWhere('user_2_id', $userId);
        })
            ->whereHas('messages')  // Contar solo los chats que tienen al menos un mensaje
            ->count();
    }
}
