<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Services\QRCodeService;
use Illuminate\Console\Command;

class UpdateOrderQRCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-qrcodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all order QR codes to point to the order show page';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $qrCodeService = new QRCodeService();
        $orders = Order::all();

        $this->info('Updating QR codes for ' . $orders->count() . ' orders...');

        $bar = $this->output->createProgressBar($orders->count());
        $bar->start();

        foreach ($orders as $order) {
            $qrCodePath = $qrCodeService->updateOrderQRCode(
                $order->id,
                $order->tracking_code,
                $order->order_number
            );

            $order->update(['qr_code' => $qrCodePath]);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('QR codes updated successfully!');

        return Command::SUCCESS;
    }
}
