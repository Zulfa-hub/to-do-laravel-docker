<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $total = Task::where('user_id', $userId)->count();
        $selesai = Task::where('user_id', $userId)->where('status', Task::STATUS_SELESAI)->count();
        $belum = $total - $selesai;
        $progress = $total > 0 ? round(($selesai / $total) * 100) : 0;

        $terlambat = Task::where('user_id', $userId)
            ->where('status', Task::STATUS_BELUM)
            ->whereNotNull('deadline')
            ->where('deadline', '<', now())
            ->count();

        $tugasTerbaru = Task::where('user_id', $userId)
            ->with('category')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $tugasMendatang = Task::where('user_id', $userId)
            ->where('status', Task::STATUS_BELUM)
            ->whereNotNull('deadline')
            ->where('deadline', '>=', now())
            ->with('category')
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'total', 'selesai', 'belum', 'progress', 'terlambat', 'tugasTerbaru', 'tugasMendatang'
        ));
    }
}
