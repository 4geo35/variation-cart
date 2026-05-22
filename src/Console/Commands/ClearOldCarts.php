<?php

namespace GIS\VariationCart\Console\Commands;

use GIS\VariationCart\Facades\CartActions;
use Illuminate\Console\Command;

class ClearOldCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:old-carts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old cards';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $carts = CartActions::getExpiredCarts();
        foreach ($carts as $cart) {
            $cart->delete();
        }
    }
}
