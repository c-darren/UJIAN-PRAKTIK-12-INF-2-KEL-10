<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Jobs\ProcessScheduledSubmissions;
use App\Models\Classroom\AssignmentSubmission;
use Illuminate\Console\Command;

class ProcessScheduledSubmissionsCommand extends Command
{
    protected $signature = 'submissions:process-scheduled {--cancel : Batalkan semua jadwal pengembalian}';
    protected $description = 'Memproses atau membatalkan tugas yang sudah dijadwalkan';

    public function handle()
    {
        if ($this->option('cancel')) {
            return $this->cancelScheduledSubmissions();
        }

        ProcessScheduledSubmissions::dispatch();
        $this->info('Job pengembalian tugas terjadwal berhasil diantrekan');
        return 0;
    }

    private function cancelScheduledSubmissions()
    {
        try {
            $count = AssignmentSubmission::where('return_status', 'scheduled')
                ->update([
                    'return_status' => 'draft',
                    'scheduled_return_at' => null
                ]);

            $this->info("Berhasil membatalkan {$count} jadwal pengembalian tugas");
            return 0;
            
        } catch (\Exception $e) {
            $this->error('Gagal membatalkan jadwal: ' . $e->getMessage());
            return 1;
        }
    }
}