<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;

class ChatController extends Controller
{
    // Mengambil riwayat pesan (umum)
    public function getMessages(Request $request, $partnerId)
    {
        $user = Auth::user();
        $userType = get_class($user);
        $partnerType = 'App\\Models\\Dosen'; // default

        $messages = ChatMessage::where(function ($query) use ($user, $userType, $partnerId, $partnerType) {
            $query->where('sender_id', $user->id)
                ->where('sender_type', $userType)
                ->where('receiver_id', $partnerId)
                ->where('receiver_type', $partnerType);
        })->orWhere(function ($query) use ($user, $userType, $partnerId, $partnerType) {
            $query->where('sender_id', $partnerId)
                ->where('sender_type', $partnerType)
                ->where('receiver_id', $user->id)
                ->where('receiver_type', $userType);
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json([
            'data' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'sender_type' => class_basename($msg->sender_type),
                    'receiver_id' => $msg->receiver_id,
                    'receiver_type' => class_basename($msg->receiver_type),
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->toIso8601String(),
                ];
            })
        ]);
    }

    // Mahasiswa mengirim pesan ke dosen
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'sender_type' => get_class($user),
            'receiver_id' => $request->receiver_id,
            'receiver_type' => 'App\\Models\\Dosen',
            'message' => $request->message,
        ]);

        return response()->json([
            'data' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_type' => class_basename($message->sender_type),
                'receiver_id' => $message->receiver_id,
                'receiver_type' => class_basename($message->receiver_type),
                'message' => $message->message,
                'created_at' => $message->created_at->toIso8601String(),
            ]
        ], 201);
    }

    // Dosen melihat daftar mahasiswa yang pernah bimbingan
    public function getDosenConversations(Request $request)
    {
        $dosen = Auth::user();
        $mahasiswas = Mahasiswa::where('dosen_id', $dosen->id)
            ->orderBy('nama', 'asc')
            ->get(['id', 'nama', 'nim', 'foto']);

        return response()->json(['data' => $mahasiswas]);
    }

    // Dosen mengambil riwayat chat dengan mahasiswa
    public function getMessagesWithMahasiswa(Request $request, $mahasiswaId)
    {
        $dosen = Auth::user();

        $messages = ChatMessage::where(function ($query) use ($dosen, $mahasiswaId) {
            $query->where('sender_id', $dosen->id)
                ->where('sender_type', 'App\\Models\\Dosen')
                ->where('receiver_id', $mahasiswaId)
                ->where('receiver_type', 'App\\Models\\Mahasiswa');
        })->orWhere(function ($query) use ($dosen, $mahasiswaId) {
            $query->where('sender_id', $mahasiswaId)
                ->where('sender_type', 'App\\Models\\Mahasiswa')
                ->where('receiver_id', $dosen->id)
                ->where('receiver_type', 'App\\Models\\Dosen');
        })
        ->orderBy('created_at', 'asc')
        ->get();

        return response()->json([
            'data' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender_id' => $msg->sender_id,
                    'sender_type' => class_basename($msg->sender_type),
                    'receiver_id' => $msg->receiver_id,
                    'receiver_type' => class_basename($msg->receiver_type),
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->toIso8601String(),
                ];
            })
        ]);
    }

    // Dosen mengirim pesan ke mahasiswa
    public function sendMessageToMahasiswa(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|integer|exists:mahasiswas,id',
            'message' => 'required|string',
        ]);

        $dosen = Auth::user();

        $message = ChatMessage::create([
            'sender_id' => $dosen->id,
            'sender_type' => get_class($dosen),
            'receiver_id' => $validated['receiver_id'],
            'receiver_type' => 'App\\Models\\Mahasiswa',
            'message' => $validated['message'],
        ]);

        return response()->json([
            'data' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'sender_type' => class_basename($message->sender_type),
                'receiver_id' => $message->receiver_id,
                'receiver_type' => class_basename($message->receiver_type),
                'message' => $message->message,
                'created_at' => $message->created_at->toIso8601String(),
            ]
        ], 201);
    }

    // Hapus pesan (oleh pengirimnya saja)
    public function destroy($id)
    {
        $user = Auth::user();
        $message = ChatMessage::findOrFail($id);

        // Perbaikan: bandingkan class basename saja
        if (
            $message->sender_id !== $user->id ||
            class_basename(get_class($user)) !== class_basename($message->sender_type)
        ) {
            return response()->json(['error' => 'Tidak diizinkan menghapus pesan ini.'], 403);
        }

        $message->delete();

        return response()->json(['message' => 'Pesan berhasil dihapus.']);
    }
}
