<?php

namespace App\Http\Controllers;

use App\Models\BookingForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublicBookingController extends Controller
{
    public function show($token)
    {
        // Validate token
        if (!cache()->has("booking_token_{$token}")) {
            abort(404, 'Link booking tidak valid atau sudah kadaluarsa.');
        }

        return view('public.booking-form', compact('token'));
    }

    public function store(Request $request, $token)
    {
        // Validate token
        if (!cache()->has("booking_token_{$token}")) {
            return response()->json([
                'success' => false,
                'message' => 'Link booking tidak valid atau sudah kadaluarsa.'
            ], 422);
        }

        // Validate form data
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'nomor_hp' => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:perempuan',
            'pekerjaan' => 'required|string|max:255',
            'alamat_ktp' => 'required|string',
            'alamat_domisili' => 'required|string',
            'tipe_kamar' => 'required|in:deluxe,premium',
            'jumlah_orang' => 'required|integer|min:1|max:10',
            'tanggal_masuk' => 'required|date|after_or_equal:today',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data yang Anda masukkan tidak valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Create booking
            $booking = BookingForm::create([
                'nama_lengkap' => $request->nama_lengkap,
                'email' => $request->email,
                'nomor_hp' => $request->nomor_hp,
                'jenis_kelamin' => $request->jenis_kelamin,
                'pekerjaan' => $request->pekerjaan,
                'alamat_ktp' => $request->alamat_ktp,
                'alamat_domisili' => $request->alamat_domisili,
                'tipe_kamar' => $request->tipe_kamar,
                'jumlah_orang' => $request->jumlah_orang,
                'tanggal_masuk' => $request->tanggal_masuk,
                'status_booking' => 'pending',
                'catatan' => $request->catatan,
            ]);

            // Remove token from cache after successful submission
            cache()->forget("booking_token_{$token}");

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dikirim! Kami akan segera menghubungi Anda.',
                'booking_id' => $booking->id
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan booking. Silakan coba lagi.'
            ], 500);
        }
    }
}