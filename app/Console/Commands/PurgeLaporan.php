<?php

namespace App\Console\Commands;

use App\Models\Laporan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class PurgeLaporan extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'laporan:purge
                            {--days=30 : Number of days after resolution before a laporan is deleted}
                            {--dry-run : Preview records that would be deleted without actually deleting them}';

    /**
     * The console command description.
     */
    protected $description = 'Delete resolved (ditindaklanjuti/ditolak) laporan older than the configured number of days, along with any attached bukti_foto.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days    = (int) $this->option('days');
        $dryRun  = $this->option('dry-run');
        $cutoff  = now()->subDays($days);

        $query = Laporan::whereIn('status', ['ditindaklanjuti', 'ditolak'])
                        ->where('updated_at', '<', $cutoff);

        $total = $query->count();

        if ($total === 0) {
            $this->info("Tidak ada laporan yang memenuhi syarat penghapusan (>{$days} hari setelah selesai).");
            return self::SUCCESS;
        }

        $this->info("Ditemukan {$total} laporan untuk dihapus (updated_at < {$cutoff->toDateString()}).");

        if ($dryRun) {
            $this->warn('[DRY RUN] Tidak ada data yang dihapus.');
            $query->select(['id_laporan', 'status', 'updated_at', 'bukti_foto'])
                  ->each(function ($l) {
                      $this->line("  - Laporan #{$l->id_laporan} [{$l->status}] updated {$l->updated_at}");
                  });
            return self::SUCCESS;
        }

        $deleted = 0;
        $query->each(function (Laporan $l) use (&$deleted) {
            if ($l->bukti_foto) {
                Storage::disk('public')->delete($l->bukti_foto);
            }
            $l->delete();
            $deleted++;
        });

        $this->info("Berhasil menghapus {$deleted} laporan beserta bukti foto terkait.");

        return self::SUCCESS;
    }
}
