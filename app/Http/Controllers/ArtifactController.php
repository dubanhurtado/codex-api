<?php

namespace App\Http\Controllers;

use App\Models\Manuscript;
use App\Services\ArtifactHunterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtifactController extends Controller
{
    protected $hunterService;

    // Inyección de dependencias: Laravel nos da el servicio automáticamente
    public function __construct(ArtifactHunterService $hunterService)
    {
        $this->hunterService = $hunterService;
    }

    /**
     * Endpoint: POST /clue
     */
    public function clue(Request $request)
    {
        // 1. Validación
        $data = $request->validate([
            'manuscript' => 'required|array',
            'manuscript.*' => 'string'
        ]);

        $manuscript = $data['manuscript'];

        // 2. Generar Huella Digital (Hash) para unicidad
        // Serializamos el array y creamos un hash SHA-256
        $hash = hash('sha256', json_encode($manuscript));

        // 3. Verificamos si ya existe en BD (Caché de persistencia)
        $existing = Manuscript::where('dna_hash', $hash)->first();

        if ($existing) {
            $hasClue = $existing->has_clue;
        } else {
            // 4. Si no existe, usamos el Servicio de Elowen (Algoritmo)
            $hasClue = $this->hunterService->containsArtifactClue($manuscript);

            // 5. Guardamos el resultado asíncronamente (Optimización)
            // Usamos create quiet para no disparar eventos innecesarios por ahora
            Manuscript::create([
                'dna_hash' => $hash,
                'content' => $manuscript,
                'has_clue' => $hasClue
            ]);
        }

        // 6. Respuesta según requerimiento:
        // Pista encontrada -> 200 OK
        // No encontrada -> 403 Forbidden
        return response()->json(
            ['has_clue' => $hasClue], // Retornamos body opcional informativo
            $hasClue ? 200 : 403
        );
    }

    /**
     * Endpoint: GET /stats
     */
    public function stats()
    {
        // Calculamos estadísticas directamente en BD
        // Para el MVP y Nivel 3, SQL COUNT es suficiente.
        
        $stats = DB::table('manuscripts')
            ->selectRaw('count(*) as count_total')
            ->selectRaw("sum(case when has_clue = 1 then 1 else 0 end) as count_clue_found")
            ->first();

        $countFound = (int) $stats->count_clue_found;
        $countTotal = (int) $stats->count_total;
        $countNoClue = $countTotal - $countFound;
        
        $ratio = $countTotal > 0 ? round($countFound / $countTotal, 2) : 0;

        return response()->json([
            'Coincidencias' => $countFound,
            'Sin Coincidencias' => $countNoClue,
            'proporcion' => $ratio
        ]);
    }
}