<?php
namespace App\Tests\Writer;

use App\Writer\WriterAssistant;
use PHPUnit\Framework\TestCase;

class WriterAssistantTest extends TestCase
{
    public function testGetLatinName()
    {
    }

    public function testGetPsalmLines()
    {
        $salmoText = <<<EOD
É aquele que caminha sem pecado*
e pratica a justiça fielmente;
que pensa a verdade no seu íntimo *
e não solta em calúnias sua língua.R. 

Que em nada prejudica o seu irmão,*
nem cobre de insultos seu vizinho;
que não dá valor algum ao homem ímpio,*
mas honra os que respeitam o Senhor.R. 

não empresta o seu dinheiro com usura,
nem se deixa subornar contra o inocente.*
Jamais vacilará quem vive assim!R.
EOD;
        $assistant = new WriterAssistant();
        $lines = $assistant->getPsalmLines($salmoText);
        $this->assertCount(3, $lines);
        $firstLine = <<<EOD
É aquele que caminha sem pecado*
e pratica a justiça fielmente;
que pensa a verdade no seu íntimo *
e não solta em calúnias sua língua.
EOD;
        $secondLine = <<<EOD
Que em nada prejudica o seu irmão,*
nem cobre de insultos seu vizinho;
que não dá valor algum ao homem ímpio,*
mas honra os que respeitam o Senhor.
EOD;
        $lastLine = <<<EOD
não empresta o seu dinheiro com usura,
nem se deixa subornar contra o inocente.*
Jamais vacilará quem vive assim!
EOD;
        $this->assertEquals($firstLine, $lines[0]);
        $this->assertEquals($secondLine, $lines[1]);
        $this->assertEquals($lastLine, $lines[2]);
    }
}
