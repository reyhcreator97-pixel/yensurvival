<?php
namespace App\Controllers;

class EmasIndogold extends BaseController
{
    private string $pageUrl = 'https://www.indogold.id/harga-emas-hari-ini';
    private string $apiUrl  = 'https://www.indogold.id/home/get_data_pricelist';

    // =====================================================
    // MODE VIEW / TES MANUAL
    // =====================================================
    public function index()
    {
        $cookieFile = WRITEPATH . 'indogold_cookie.txt';
        [$html] = $this->httpGet($this->pageUrl, $cookieFile);
        $token  = $this->extractToken($html ?? '');
        if (!$token) return $this->fail('Token tidak ditemukan.');

        $json = $this->fetchPriceJson($token, $cookieFile);
        if (!$json || !isset($json['data']['data_denom'])) {
            return $this->fail('Harga tidak ditemukan (token atau session gagal).');
        }

        $harga     = $json['data']['data_denom'];
        $harga1g   = $harga['1.0'] ?? null;
        $updatedAt = date('d-m-Y H:i');

        return view('indogold/index', compact('harga', 'updatedAt'));
    }

    // =====================================================
    // MODE UNTUK DASHBOARD
    // =====================================================
    public function getHarga1Gram(): ?array
    {
        $cookieFile = WRITEPATH . 'indogold_cookie.txt';
        [$html] = $this->httpGet($this->pageUrl, $cookieFile);
        $token  = $this->extractToken($html ?? '');
        if (!$token) return null;

        $json = $this->fetchPriceJson($token, $cookieFile);
        if (!$json || !isset($json['data']['data_denom']['1.0'])) return null;

        $harga1g = $json['data']['data_denom']['1.0'];
        return [
            'Antam'      => $harga1g['Antam'] ?? 0,
            'UBS'        => $harga1g['UBS'] ?? 0,
            'updated_at' => date('d-m-Y H:i'),
        ];
    }

    // =====================================================
    // HELPER PRIVATE FUNCTIONS
    // =====================================================
    private function httpGet(string $url, string $cookieFile, bool $fresh=false): array
    {
        if ($fresh && is_file($cookieFile)) @unlink($cookieFile);
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEJAR      => $cookieFile,
            CURLOPT_COOKIEFILE     => $cookieFile,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept: text/html',
            ],
        ]);
        $html = curl_exec($ch);
        $err  = curl_error($ch);
        curl_close($ch);
        return [$html, $err];
    }

    private function extractToken(string $html): ?string
    {
        $patterns = [
            '/simulasi-token["\']\s*,\s*["\']([a-f0-9]{24,40})["\']/i',
            '/"simulasi-token"\s*:\s*"([a-f0-9]{24,40})"/i',
            '/name=["\']simulasi-token["\'][^>]*value=["\']([a-f0-9]{24,40})["\']/i',
            '/simulasi-token=([a-f0-9]{24,40})/i',
        ];
        foreach ($patterns as $p) {
            if (preg_match($p, $html, $m)) return $m[1];
        }
        return null;
    }

    private function fetchPriceJson(string $token, string $cookieFile): ?array
    {
        $post = http_build_query([
            'form'            => json_encode(['product' => 'comparison_antamxubs']),
            'simulasi-token'  => $token,
        ]);

        $ch = curl_init($this->apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_COOKIEJAR      => $cookieFile,
            CURLOPT_COOKIEFILE     => $cookieFile,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $post,
            CURLOPT_TIMEOUT        => 20,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/x-www-form-urlencoded; charset=UTF-8',
                'X-Requested-With: XMLHttpRequest',
                'Origin: https://www.indogold.id',
                'Referer: https://www.indogold.id/harga-emas-hari-ini',
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            ],
        ]);
        $resp = curl_exec($ch);
        curl_close($ch);

        return json_decode($resp, true);
    }

    private function fail(string $msg)
    {
        return "<div style='padding:12px;border:1px solid #f5c2c7;background:#f8d7da;color:#842029;border-radius:8px'>{$msg}</div>";
    }
}
