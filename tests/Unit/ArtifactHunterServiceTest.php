<?php

namespace Tests\Unit;

use App\Services\ArtifactHunterService;
use PHPUnit\Framework\TestCase;

class ArtifactHunterServiceTest extends TestCase
{
    private ArtifactHunterService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ArtifactHunterService();
    }

    public function test_it_detects_horizontal_clue()
    {
        //Agregamos uan coincidencia en el primer elemento
        $manuscript = [
            "AAAAGM", 
            "XRLORE",
            "NARURR",
            "REVRAL",
            "EGSILE",
            "BRINDS"
        ];
        $this->assertTrue($this->service->containsArtifactClue($manuscript));
    }

    public function test_it_detects_diagonal_clue_from_example()
    {
        // Ejemplo exacto del documento de Galatea,Diagonal R en (0,0) a (3,3) 
        $manuscript = [
            "RTHGQW",
            "XRLORE",
            "NARURR",
            "REVRAL",
            "EGSILE",
            "BRINDS"
        ];
        $this->assertTrue($this->service->containsArtifactClue($manuscript));
    }

    public function test_it_returns_false_when_no_clue_exists()
    {
        $manuscript = [
            "ABCDEF",
            "GHIJKL",
            "MNOPQR",
            "STUVWX",
            "YZABCD",
            "EFGHIJ"
        ];
        $this->assertFalse($this->service->containsArtifactClue($manuscript));
    }
}