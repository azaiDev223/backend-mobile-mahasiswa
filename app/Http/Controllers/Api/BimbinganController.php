<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BimbinganResource;
use App\Models\Bimbingan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BimbinganController extends Controller
{
    public function bimbinganDosen(Request $request)
{
    $user = $request->user();

    // validasi user adalah dosen
    if (!$user || !$user instanceof \App\Models\Dosen) {
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    $bimbingan = Bimbingan::with(['mahasiswa', 'dosen'])
        ->where('dosen_id', $user->id)
        ->get();

    return BimbinganResource::collection($bimbingan);
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mahasiswa_id' => 'required|exists:mahasiswas,id',
            'dosen_id' => 'required|exists:dosens,id',
            'tanggal_bimbingan' => 'required|date',
            'topik' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'status' => 'required|in:Terjadwal,Selesai,Dibatalkan,Ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bimbingan = Bimbingan::create($validator->validated());

        return new BimbinganResource($bimbingan);
    }

    public function show($id)
    {
        $bimbingan = Bimbingan::with(['mahasiswa', 'dosen'])->findOrFail($id);
        return new BimbinganResource($bimbingan);
    }

    public function update(Request $request, $id)
    {
        $bimbingan = Bimbingan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'mahasiswa_id' => 'sometimes|exists:mahasiswas,id',
            'dosen_id' => 'sometimes|exists:dosens,id',
            'tanggal_bimbingan' => 'sometimes|date',
            'topik' => 'sometimes|string|max:255',
            'catatan' => 'nullable|string',
            'status' => 'sometimes|in:Terjadwal,Selesai,Dibatalkan,Ditolak',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $bimbingan->update($validator->validated());

        return new BimbinganResource($bimbingan);
    }

    public function destroy($id)
    {
        $bimbingan = Bimbingan::findOrFail($id);
        $bimbingan->delete();

        return response()->json(['message' => 'Data bimbingan berhasil dihapus.']);
    }
}
