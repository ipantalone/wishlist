<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportCSV extends Command
{
    protected $signature = 'export:csv';
    protected $description = "Export user's wishlist on CSV";

    public function handle()
    {
        $file = "export_wishlists.csv";

        $handle = fopen(storage_path($file), 'w'); // sovrascrivo ogni volta il csv precedente
        fputcsv($handle, ['user', 'title wishlist', 'number of items'], ';');
        
        DB::table('wishlists as w')->select('u.id_user', 'w.title', DB::raw('count(p.id_product) as products'))
            ->join('users as u', 'u.id_user', '=', 'w.id_user')
            ->leftJoin('products as p', 'p.id_wishlist', '=', 'w.id_wishlist')
            ->groupBy('u.id_user', 'w.title')
            ->orderBy('u.id_user')
            ->orderBy('w.id_wishlist')
            ->chunk(50, function ($data) use($handle) {
                $data->each(function ($item, $key) use($handle) {
                    fputcsv($handle, (array)$item);
                });
            });

        fclose($handle);

        $this->info("Generated. Go to " . storage_path($file));
    }
}