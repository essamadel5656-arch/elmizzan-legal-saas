<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class PwaManifestController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $manifest = [
            "name" => Setting::get('app_name', 'الميزان'),
            "short_name" => Setting::get('pwa_short_name', 'الميزان'),
            "description" => Setting::get('pwa_description', 'نظام إدارة القضايا القانونية المتكاملة'),
            "start_url" => "/home",
            "display" => "standalone",
            "orientation" => "portrait",
            "theme_color" => Setting::get('pwa_theme_color', '#1e293b'),
            "background_color" => Setting::get('pwa_background_color', '#f8fafc'),
            "lang" => "ar",
            "dir" => "rtl",
            "icons" => [
                [
                    "src" => "/android-chrome-192x192.png",
                    "sizes" => "192x192",
                    "type" => "image/png"
                ],
                [
                    "src" => "/android-chrome-512x512.png",
                    "sizes" => "512x512",
                    "type" => "image/png",
                    "purpose" => "any maskable"
                ]
            ]
        ];

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
