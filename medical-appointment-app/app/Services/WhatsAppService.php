<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = env('CALLMEBOT_API_KEY', '8662488');
        $this->apiUrl = "https://api.callmebot.com/whatsapp.php";
    }

    /**
     * Send a WhatsApp message using CallMeBot API.
     *
     * @param string $to Recipient phone number.
     * @param string $message The message content.
     * @return bool
     */
    public function sendMessage($to, $message)
    {
        try {
            // Limpiar número de teléfono
            $to = preg_replace('/[^0-9]/', '', $to);

            // Si tiene 10 dígitos (México), anteponer 521
            if (strlen($to) == 10) {
                $to = '521' . $to;
            }

            Log::info("Enviando WhatsApp a {$to} usando CallMeBot");

            // Modo desarrollo opcional
            if (env('WHATSAPP_DRIVER') === 'log') {
                Log::info("DEV MODE - WhatsApp simulated to {$to}: {$message}");
                return true;
            }

            $response = Http::withoutVerifying()
                ->get($this->apiUrl, [
                    'phone' => $to,
                    'text' => $message,
                    'apikey' => $this->apiKey
                ]);

            if ($response->failed()) {
                Log::error("Error en CallMeBot API: " . $response->body());
                return false;
            }

            Log::info("WhatsApp enviado exitosamente vía CallMeBot");
            return true;

        } catch (\Exception $e) {
            Log::error("Excepción en WhatsAppService: " . $e->getMessage());
            return false;
        }
    }
}
