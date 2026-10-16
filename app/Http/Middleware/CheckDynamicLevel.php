<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Level;

class CheckDynamicLevel
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthorized.');
        }

        // Ambil parameter level dari URL (prefix {level})
        $targetLevelSlug = $request->route('level');

        if (!$targetLevelSlug) {
            abort(404, 'Level tidak ditemukan di URL.');
        }

        // Cari level berdasarkan slug di database
        $levelData = Level::where('slug', $targetLevelSlug)->first();

        if (!$levelData) {
            abort(404, 'Level akses tidak terdaftar.');
        }

        // Validasi apakah user terdaftar ke level ini dan membership masih aktif
        $hasAccess = $user->levels()
            ->where('levels.id', $levelData->id)
            ->where(function ($query) {
                $query->whereNull('membership_expired_at')
                    ->orWhere('membership_expired_at', '>', now());
            })
            ->exists();

        if (!$hasAccess) {
            abort(403, 'Akses ditolak: Anda tidak memiliki hak atau membership aktif untuk level ini.');
        }

        return $next($request);
    }
}
