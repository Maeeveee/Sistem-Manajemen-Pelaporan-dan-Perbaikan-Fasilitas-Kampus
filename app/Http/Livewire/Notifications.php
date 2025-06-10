<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\LaporanKerusakan;
use Illuminate\Support\Facades\Auth;

class Notifications extends Component
{
    public $notifications = [];

    private function generateNotifications($user, $role)
    {
        $notifications = [];

        if (in_array($role, [1, 2, 3])) {
            $reports = LaporanKerusakan::where('nama_pelapor', $user->name)->get();

            foreach ($reports as $report) {
                $message = $this->getUserNotificationMessage($report);
                $notifications[] = [
                    'message' => $message,
                    'time' => $report->updated_at->diffForHumans(),
                    'report_id' => $report->id,
                    'foto' => $report->foto,
                ];
            }
        } elseif ($role === 6) {
            $reports = LaporanKerusakan::where('status_admin', 'pending')->get();

            foreach ($reports as $report) {
                $notifications[] = [
                    'message' => "Laporan baru dari {$report->nama_pelapor} menunggu verifikasi.",
                    'time' => $report->updated_at->diffForHumans(),
                    'report_id' => $report->id,
                    'foto' => $report->foto,
                ];
            }
        } elseif ($role === 4) {
            $reports = LaporanKerusakan::where('status_admin', 'verifikasi')
                ->where('status_sarpras', 'pending')
                ->get();

            foreach ($reports as $report) {
                $notifications[] = [
                    'message' => "Laporan dari {$report->nama_pelapor} sudah diverifikasi admin, menunggu persetujuan Anda.",
                    'time' => $report->updated_at->diffForHumans(),
                    'report_id' => $report->id,
                    'foto' => $report->foto,
                ];
            }
        } elseif ($role === 5) {
            $reports = LaporanKerusakan::where('status_sarpras', 'approved')
                ->where('status_teknisi', 'pending')
                ->get();

            foreach ($reports as $report) {
                $notifications[] = [
                    'message' => "Laporan dari {$report->nama_pelapor} sudah disetujui sarpras, siap untuk diperbaiki.",
                    'time' => $report->updated_at->diffForHumans(),
                    'report_id' => $report->id,
                    'foto' => $report->foto,
                ];
            }
        }

        return $notifications;
    }

    private function getUserNotificationMessage($report)
    {
        if ($report->status_admin === 'pending') {
            return "Laporan Anda menunggu verifikasi admin.";
        } elseif ($report->status_admin === 'verifikasi' && $report->status_sarpras === 'pending') {
            return "Laporan Anda sudah diverifikasi admin, menunggu persetujuan sarpras.";
        } elseif ($report->status_sarpras === 'approved' && $report->status_teknisi === 'pending') {
            return "Laporan Anda sudah disetujui sarpras, menunggu teknisi.";
        } elseif ($report->status_teknisi === 'sedang dikerjakan') {
            return "Laporan Anda sedang dikerjakan oleh teknisi.";
        } elseif ($report->status_teknisi === 'selesai') {
            return "Laporan Anda telah selesai diperbaiki.";
        } elseif ($report->status_admin === 'reject') {
            return "Laporan Anda telah ditolak oleh admin.";
        } else {
            return "Status laporan Anda tidak diketahui.";
        }
    }

    public function render()
    {
        $user = Auth::user();
        $role = $user->role_id;
        $this->notifications = $this->generateNotifications($user, $role);
        return view('livewire.notifications');
    }
}
