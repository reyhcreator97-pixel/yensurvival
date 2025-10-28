<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class OptionalLogin implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Biar halaman publik gak error kalau user_id() dipanggil
        if (!function_exists('user_id')) {
            helper('auth');
        }
        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Gak ngapa-ngapain setelahnya
    }
}