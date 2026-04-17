<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AntriStand;
use App\Models\QuotaStand;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AntrianController extends Controller
{
    /**
     * Display a listing of all bookings.
     */
    public function index()
    {
        $bookings = AntriStand::with('stand')
            ->orderBy('tanggal_pesan', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    /**
     * Store a newly created booking in storage.
     */
    public function store(Request $request)
    {
        // Validate input with proper date format (Y-m-d)
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'tanggal_pesan' => 'required|date_format:Y-m-d',
            'kd_stand' => 'required|in:FT,LK',
        ]);

        $kdStand = $validated['kd_stand'];
        $tanggalPesan = $validated['tanggal_pesan'];
        $email = $validated['email'];

        // Get the stand and its quota
        $stand = QuotaStand::where('kd_stand', $kdStand)->firstOrFail();

        // Check 1: Is quota full for this stand+date?
        $bookingCount = AntriStand::where('kd_stand', $kdStand)
            ->where('tanggal_pesan', $tanggalPesan)
            ->count();

        if ($bookingCount >= $stand->kuota) {
            return response()->json([
                'success' => false,
                'message' => 'Antrian sudah penuh, silakan pilih tanggal lain',
            ], 422);
        }

        // Check 2: Has this email already booked this stand on this date?
        $existingBooking = AntriStand::where('email', $email)
            ->where('kd_stand', $kdStand)
            ->where('tanggal_pesan', $tanggalPesan)
            ->first();

        if ($existingBooking) {
            return response()->json([
                'success' => false,
                'message' => 'Email ini sudah memesan stand ini pada tanggal tersebut',
            ], 422);
        }

        // Generate nomor_antri
        $counter = $bookingCount + 1;
        $formattedDate = date('Ymd', strtotime($tanggalPesan));
        $formattedCounter = str_pad($counter, 3, '0', STR_PAD_LEFT);
        $nomorAntri = $kdStand . $formattedDate . $formattedCounter;

        // Create booking
        $booking = AntriStand::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'tanggal_pesan' => $tanggalPesan,
            'kd_stand' => $kdStand,
            'nomor_antri' => $nomorAntri,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat',
            'data' => $booking->load('stand'),
        ], 201);
    }

    /**
     * Display the specified booking.
     */
    public function show($id)
    {
        $booking = AntriStand::with('stand')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $booking,
        ]);
    }

    /**
     * Delete the specified booking.
     */
    public function destroy($id)
    {
        $booking = AntriStand::findOrFail($id);
        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dihapus',
        ]);
    }

    /**
     * Get quota status for all stands on a specific date or all dates.
     */
    public function quota(Request $request)
    {
        $tanggalPesan = $request->query('tanggal_pesan');

        $stands = QuotaStand::all();
        $quotaStatus = [];

        foreach ($stands as $stand) {
            $query = AntriStand::where('kd_stand', $stand->kd_stand);

            if ($tanggalPesan) {
                $query->where('tanggal_pesan', $tanggalPesan);
            }

            $booked = $query->count();
            $available = $stand->kuota - $booked;

            $quotaStatus[] = [
                'kd_stand' => $stand->kd_stand,
                'nama_stand' => $stand->nama_stand,
                'kuota' => $stand->kuota,
                'terpesan' => $booked,
                'tersedia' => max(0, $available),
                'tanggal_pesan' => $tanggalPesan,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $quotaStatus,
        ]);
    }
}
