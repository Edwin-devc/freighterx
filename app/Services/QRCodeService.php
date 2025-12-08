<?php

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class QRCodeService
{
    /**
     * Generate QR code for an order
     *
     * @param string $trackingCode
     * @param string $orderNumber
     * @return string Path to the generated QR code
     */
    public function generateOrderQRCode(string $trackingCode, string $orderNumber, ?int $orderId = null): string
    {
        // Create QR code data - URL to the order show page
        if ($orderId) {
            $qrData = route('orders.show', $orderId);
        } else {
            // Fallback for seeding - will be updated later
            $qrData = url('/orders?tracking=' . $trackingCode);
        }

        // Generate QR code with simplified approach
        $qrCode = new QrCode($qrData);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // Define the storage path
        $filename = 'qrcodes/' . $orderNumber . '.png';

        // Save to storage
        Storage::disk('public')->put($filename, $result->getString());

        return $filename;
    }

    /**
     * Update QR code for an existing order
     *
     * @param int $orderId
     * @param string $trackingCode
     * @param string $orderNumber
     * @return string Path to the generated QR code
     */
    public function updateOrderQRCode(int $orderId, string $trackingCode, string $orderNumber): string
    {
        // Delete old QR code if it exists
        $oldPath = 'qrcodes/' . $orderNumber . '.png';
        $this->deleteQRCode($oldPath);

        // Generate new QR code with order ID
        return $this->generateOrderQRCode($trackingCode, $orderNumber, $orderId);
    }    /**
     * Delete QR code file
     *
     * @param string $path
     * @return bool
     */
    public function deleteQRCode(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }

        return false;
    }
}
