<?php

namespace App\Http\Controllers;

use App\Models\ComplaintForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicComplaintController extends Controller
{
    public function show($token)
    {
        // Validate token
        if (!cache()->has("complaint_token_{$token}")) {
            abort(404, 'Link complaint form tidak valid atau sudah kadaluarsa.');
        }

        return view('public.complaint-form', compact('token'));
    }

    public function store(Request $request, $token)
    {
        // Validate token
        if (!cache()->has("complaint_token_{$token}")) {
            return response()->json([
                'success' => false,
                'message' => 'Link complaint form tidak valid atau sudah kadaluarsa.'
            ], 422);
        }

        // Validate form data
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_hp' => 'required|string|max:20',
            'tipe_kamar' => 'required|in:' . implode(',', array_keys(ComplaintForm::getTipeKamarOptions())),
            'subjek' => 'required|string|max:255',
            'kategori' => 'required|in:' . implode(',', array_keys(ComplaintForm::getKategoriOptions())),
            'deskripsi' => 'required|string|min:10',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nomor_hp.required' => 'Nomor HP wajib diisi.',
            'tipe_kamar.required' => 'Tipe kamar wajib dipilih.',
            'subjek.required' => 'Subjek complaint wajib diisi.',
            'kategori.required' => 'Kategori complaint wajib dipilih.',
            'deskripsi.required' => 'Deskripsi complaint wajib diisi.',
            'deskripsi.min' => 'Deskripsi complaint minimal 10 karakter.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang Anda masukkan tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create complaint
            $complaint = ComplaintForm::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'nomor_hp' => $request->nomor_hp,
                'tipe_kamar' => $request->tipe_kamar,
                'subjek' => $request->subjek,
                'kategori' => $request->kategori,
                'deskripsi' => $request->deskripsi,
                'status_komplain' => 'Open',
                'token_used' => $token,
            ]);

            // Remove token from cache after successful submission
            cache()->forget("complaint_token_{$token}");

            return response()->json([
                'success' => true,
                'message' => 'Complaint berhasil dikirim! Kami akan segera menangani keluhan Anda.',
                'complaint_id' => $complaint->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan complaint. Silakan coba lagi.'
            ], 500);
        }
    }
}