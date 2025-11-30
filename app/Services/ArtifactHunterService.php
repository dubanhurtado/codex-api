<?php

namespace App\Services;

class ArtifactHunterService
{
    /**
     * Analiza el manuscrito en busca de secuencias de 4 letras idénticas.
     */
    public function containsArtifactClue(array $manuscript): bool
    {
        if (empty($manuscript)) {
            return false;
        }

        $n = count($manuscript);
        // Matriz de caracteres
        $matrix = array_map('str_split', $manuscript);

        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                // Pasamos enteros explicitos.
                if (
                    $this->checkDirection($matrix, $i, $j, 0, 1, $n) ||  // Horizontal
                    $this->checkDirection($matrix, $i, $j, 1, 0, $n) ||  // Vertical
                    $this->checkDirection($matrix, $i, $j, 1, 1, $n) ||  // Diagonal \
                    $this->checkDirection($matrix, $i, $j, 1, -1, $n)    // Diagonal /
                ) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Verifica una dirección especifica.
     */
    private function checkDirection(array $matrix, int $row, int $col, int $dRow, int $dCol, int $n): bool
    {
        // PROTECCIÓN EXTRA: Aseguramos que dRow y dCol sean enteros matemáticos
        $dRow = intval($dRow);
        $dCol = intval($dCol);

        $lastRow = $row + (3 * $dRow);
        $lastCol = $col + (3 * $dCol);

        if ($lastRow < 0 || $lastRow >= $n || $lastCol < 0 || $lastCol >= $n) {
            return false;
        }

        $char = $matrix[$row][$col];
        
        for ($k = 1; $k < 4; $k++) {
            if ($matrix[$row + ($k * $dRow)][$col + ($k * $dCol)] !== $char) {
                return false;
            }
        }

        return true;
    }
}