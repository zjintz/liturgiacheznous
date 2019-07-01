<?php
namespace App\Tests\Util;

use App\Util\IgrejaSantaInesFilter;
use App\Tests\Util\BaseFilterTest;


class IgrejaSantaInesFilterDomingoTest extends BaseFilterTest
{    
    public function testFilter()
    {
        $iFilter = new IgrejaSantaInesFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleSantaInesDomingo.html");
        $liturgyText = $iFilter->filter($data);
        $temporalText = $liturgyText["temporal"];
        $santoralText = $liturgyText["santoral"];
        $this->assertEquals("Success", $temporalText["status"]);
        $this->assertEquals("Success", $santoralText["status"]);
        $this->assertEquals(true, $temporalText["hasL2"]);
        $this->assertEquals(false, $santoralText["hasL2"]);

        $this->assertEquals("30/06/2019 (DOMINGO)", $liturgyText["dayTitle"]);
        $this->assertTemporalTitles($temporalText);
        $this->assertTemporalContent($temporalText);
    }
    
    protected function assertTemporalTitles($temporalText)
    {
        $this->assertEquals(
            "1a Leitura - ANO C 1Rs 19,16b.19-21",
            $temporalText["l1Title"]
        );
        
        $this->assertEquals(
            "Salmo - ANO C Sl 15,1-2a.5.7-8.9-10.11(R. 5a)",
            $temporalText["salmoTitle"]
        );

        $this->assertEquals(
            "2a Leitura - ANO C Gl 5,1.13-18",
            $temporalText["l2Title"]
        );

        $this->assertEquals(
            "Evangelho - ANO C Lc 9,51-62",
            $temporalText["gospelTitle"]
        );
    }

    protected function assertTemporalContent($temporalText)
    {
        $l1Intro = "Eliseu levantou-se e seguiu Elias.";
        $l1Subtitle= "Leitura do Primeiro Livro dos Reis 19, 16b. 19-21";
        $l2Intro = "Fostes chamados para a liberdade.";
        $l2Subtitle= "Leitura da Carta de São Paulo aos Gálatas 5, 1. 13-18";
        $l2Text = <<<EOD
Irmãos: É para a liberdade que Cristo nos libertou. Ficai pois firmes e não vos deixeis amarrar de novo ao jugo da escravidão. Sim, irmãos, fostes chamados para a liberdade. Porém, não façais dessa liberdade um pretexto para servirdes à carne. Pelo contrário, fazei-vos escravos uns dos outros, pela caridade. Com efeito, toda a Lei se resume neste único mandamento: 'Amarás o teu próximo como a ti mesmo'. Mas, se vos mordeis e vos devorais uns aos outros, cuidado para não serdes consumidos uns pelos outros. Eu vos ordeno: Procedei segundo o Espírito. Assim, não satisfareis aos desejos da carne. Pois a carne tem desejos contra o espírito, e o espírito tem desejos contra a carne. Há uma oposição entre carne e espírito, de modo que nem sempre fazeis o que gostaríeis de fazer. Se, porém, sois conduzidos pelo Espírito, então não estais sob o jugo da Lei. Palavra do Senhor. 
EOD;
        
        $salmoChorus = "Ó Senhor, sois minha herança para sempre!";
        $gospelIntro = "Jesus tomou a firme decisão de partir para Jerusalém. 'Eu te seguirei para onde quer que fores'.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo segundo Lucas 9, 51-62";
        $gospelText = <<<EOD
Estava chegando o tempo de Jesus ser levado para o céu. Então ele tomou a firme decisão de partir para Jerusaléme enviou mensageiros à sua frente. Estes puseram-se a caminho e entraram num povoado de samaritanos, para preparar hospedagem para Jesus. Mas os samaritanos não o receberam, pois Jesus dava a impressão de que ia a Jerusalém. Vendo isso, os discípulos Tiago e João disseram: 'Senhor, queres que mandemos descer fogo do céu para destruí-los?'Jesus, porém, voltou-se e repreendeu-os. E partiram para outro povoado. Enquanto estavam caminhando, alguém na estrada disse a Jesus: 'Eu te seguirei para onde quer que fores. 'Jesus lhe respondeu: 'As raposas têm tocas e os pássaros têm ninhos; mas o Filho do Homem não tem onde repousar a cabeça. 'Jesus disse a outro: 'Segue-me. ' Este respondeu: 'Deixa-me primeiro ir enterrar meu pai. 'Jesus respondeu: 'Deixa que os mortos enterrem os seus mortos; mas tu, vai anunciar o Reino de Deus. 'Um outro ainda lhe disse: 'Eu te seguirei, Senhor, mas deixa-me primeiro despedir-me dos meus familiares. 'Jesus, porém, respondeu-lhe: 'Quem põe a mão no arado e olha para trás, não está apto para o Reino de Deus. 'Palavra da Salvação. 
EOD;
    
        $this->assertEquals($l1Subtitle, $temporalText["l1Subtitle"]);
        $this->assertEquals($l1Intro, $temporalText["l1Intro"]);
        $this->assertEquals($salmoChorus, $temporalText["salmoChorus"]);
        $this->assertEquals($l2Subtitle, $temporalText["l2Subtitle"]);
        $this->assertEquals($l2Intro, $temporalText["l2Intro"]);
        $this->assertEquals($l2Text, $temporalText["l2Text"]);
        $this->assertEquals($gospelSubtitle, $temporalText["gospelSubtitle"]);
        $this->assertEquals($gospelIntro, $temporalText["gospelIntro"]);
        $this->assertEquals($gospelText, $temporalText["gospelText"]);
    
    }

}
    

