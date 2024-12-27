<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Classroom\AssignmentSubmission;

class ProcessScheduledSubmissions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            DB::beginTransaction();

            // Ambil submission yang sudah melewati jadwal
            $submissions = AssignmentSubmission::where('return_status', 'scheduled')
                ->where('scheduled_return_at', '<=', Carbon::now())
                ->get();

            foreach ($submissions as $submission) {
                Log::info('Memproses submission ID: ' . $submission->id);
                Log::info('Jadwal: ' . $submission->scheduled_return_at . ' | Sekarang: ' . Carbon::now());
                
                $submission->update([
                    'return_status' => 'returned',
                    'returned_at' => now()
                ]);
            }

            DB::commit();
            Log::info('Berhasil memproses ' . $submissions->count() . ' tugas terjadwal');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal memproses tugas terjadwal: ' . $e->getMessage());
            throw $e;
        }
    }
}