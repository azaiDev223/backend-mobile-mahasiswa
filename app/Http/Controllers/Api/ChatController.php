<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ChatMessage;
use Illuminate\Support\Facades\Auth;
use App\Models\Mahasiswa;

class ChatController extends Controller
{
    // Mengambil riwayat pesan antara user yang login dan user lain
    public function getMessages(Request $request, $partnerId)
    {
        $user = Auth::user();
        $userType = get_class($user);

        // Cari partner berdasarkan ID (untuk sekarang kita asumsikan partner adalah Dosen)
        // Di masa depan, ini bisa dibuat lebih dinamis
        $partnerType = 'App\\Models\\Dosen';

        $messages = ChatMessage::where(function ($query) use ($user, $userType, $partnerId, $partnerType) {
            // Pesan dari saya ke dia
            $query->where('sender_id', $user->id)
                  ->where('sender_type', $userType)
                  ->where('receiver_id', $partnerId)
                  ->where('receiver_type', $partnerType);
        })->orWhere(function ($query) use ($user, $userType, $partnerId, $partnerType) {
            // Pesan dari dia ke saya
            $query->where('sender_id', $partnerId)
                  ->where('sender_type', $partnerType)
                  ->where('receiver_id', $user->id)
                  ->where('receiver_type', $userType);
        })
        ->orderBy('created_at', 'asc') // Urutkan dari yang terlama
        ->get();

        return response()->json(['data' => $messages]);
    }

    // Mengirim pesan baru
    public function sendMessage(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer',
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        // Buat pesan baru
        $message = ChatMessage::create([
            'sender_id' => $user->id,
            'sender_type' => get_class($user),
            'receiver_id' => $request->receiver_id,
            'receiver_type' => 'App\\Models\\Dosen', // Asumsi mahasiswa chat dengan dosen
            'message' => $request->message,
        ]);

        return response()->json(['data' => $message], 201);
    }

    public function getDosenConversations(Request $request)
    {
        $dosen = Auth::user();
        $mahasiswas = Mahasiswa::where('dosen_id', $dosen->id)
                                ->orderBy('nama', 'asc')
                                ->get(['id', 'nama', 'nim', 'foto']);
        
        return response()->json(['data' => $mahasiswas]);
    }

    /**
     * DOSEN: Mengambil riwayat pesan dengan seorang mahasiswa tertentu.
     */
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

        return response()->json(['data' => $messages]);
    }

    /**
     * DOSEN: Mengirim pesan ke seorang mahasiswa.
     */
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

        return response()->json(['data' => $message], 201);
    }
}
