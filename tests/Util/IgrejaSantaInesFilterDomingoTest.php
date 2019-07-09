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
        $liturgyText = $iFilter->filter($data , "2019-06-30");
        $temporalSection = $liturgyText->getTemporalSection();
        $santoralSection = $liturgyText->getSantoralSection();
        $this->assertEquals("30/06/2019 (DOMINGO)", $liturgyText->getDayTitle());
        $this->assertEquals(new \DateTime("2019-06-30"), $liturgyText->getDate());
        $l1Title = "1a Leitura - ANO C 1Rs 19,16b.19-21";
        $l1Reference = "1Rs 19,16b.19-21";
        $l1Intro = "Eliseu levantou-se e seguiu Elias.";
        $l1Subtitle= "Leitura do Primeiro Livro dos Reis 19, 16b. 19-21";
        $l1Text =<<<EOD
Naqueles dias: disse o Senhor a Elias: vai e ungea Eliseu, filho de Safat, de Abel-Meula, como profeta em teu lugar. Elias partiu dali e encontrou Eliseu, filho de Safat, lavrando a terra com doze juntas de bois; e ele mesmo conduzia a última. Elias, ao passar perto de Eliseu, lançou sobre ele o seu manto. Então Eliseu deixou os bois e correu atrás de Elias, dizendo: 'Deixa-me primeiro ir beijar meu pai e minha móe, depois te seguirei'. Elias respondeu: 'Vai e volta! Pois o que te fiz eu?Ele retirou-se, tomou a junta de bois e os imolou. Com a madeira do arado e da canga assou a carne e deu de comer à sua gente. Depois levantou-se, seguiu Elias e pôs-se ao seu serviço. Palavra do Senhor. 
EOD;
        
        $l2Title = "2a Leitura - ANO C Gl 5,1.13-18";
        $l2Reference = "Gl 5,1.13-18";
        $l2Intro = "Fostes chamados para a liberdade.";
        $l2Subtitle= "Leitura da Carta de São Paulo aos Gálatas 5, 1. 13-18";
        $l2Text = <<<EOD
Irmãos: É para a liberdade que Cristo nos libertou. Ficai pois firmes e não vos deixeis amarrar de novo ao jugo da escravidão. Sim, irmãos, fostes chamados para a liberdade. Porém, não façais dessa liberdade um pretexto para servirdes à carne. Pelo contrário, fazei-vos escravos uns dos outros, pela caridade. Com efeito, toda a Lei se resume neste único mandamento: 'Amarás o teu próximo como a ti mesmo'. Mas, se vos mordeis e vos devorais uns aos outros, cuidado para não serdes consumidos uns pelos outros. Eu vos ordeno: Procedei segundo o Espírito. Assim, não satisfareis aos desejos da carne. Pois a carne tem desejos contra o espírito, e o espírito tem desejos contra a carne. Há uma oposição entre carne e espírito, de modo que nem sempre fazeis o que gostaríeis de fazer. Se, porém, sois conduzidos pelo Espírito, então não estais sob o jugo da Lei. Palavra do Senhor. 
EOD;
        $salmoTitle = "Salmo - ANO C Sl 15,1-2a.5.7-8.9-10.11(R. 5a)";
        $salmoChorus = "Ó Senhor, sois minha herança para sempre!";
        $salmoText = <<<EOD
Guardai-me, ó Deus, porque em vós me refugio!Digo ao Senhor: 'Somente vós sois meu Senhor: / nenhum bem eu posso achar fora de vós!'Ó Senhor, sois minha herança e minha taça, / meu destino está seguro em vossas mãos!
R.
 Eu bendigo o Senhor, que me aconselha, / e até de noite me adverte o coração. Tenho sempre o Senhor ante meus olhos, / pois se o tenho a meu lado não vacilo.
R.
Eis por que meu coração está em festa, / minha alma rejubila de alegria, / e até meu corpo no repouso está tranqüilo; pois não haveis de me deixar entregue à morte, / nem vosso amigo conhecer a corrupção.
R.
Vós me ensinais vosso caminho para a vida; / junto a vós, felicidade sem limites, / delícia eterna e alegria ao vosso lado!
R.
EOD;
        $gospelTitle = "Evangelho - ANO C Lc 9,51-62";
        $gospelIntro = "Jesus tomou a firme decisão de partir para Jerusalém. 'Eu te seguirei para onde quer que fores'.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo segundo Lucas 9, 51-62";
        $gospelAuthor = "Lucas";
        $gospelText = <<<EOD
Estava chegando o tempo de Jesus ser levado para o céu. Então ele tomou a firme decisão de partir para Jerusaléme enviou mensageiros à sua frente. Estes puseram-se a caminho e entraram num povoado de samaritanos, para preparar hospedagem para Jesus. Mas os samaritanos não o receberam, pois Jesus dava a impressão de que ia a Jerusalém. Vendo isso, os discípulos Tiago e João disseram: 'Senhor, queres que mandemos descer fogo do céu para destruí-los?'Jesus, porém, voltou-se e repreendeu-os. E partiram para outro povoado. Enquanto estavam caminhando, alguém na estrada disse a Jesus: 'Eu te seguirei para onde quer que fores. 'Jesus lhe respondeu: 'As raposas têm tocas e os pássaros têm ninhos; mas o Filho do Homem não tem onde repousar a cabeça. 'Jesus disse a outro: 'Segue-me. ' Este respondeu: 'Deixa-me primeiro ir enterrar meu pai. 'Jesus respondeu: 'Deixa que os mortos enterrem os seus mortos; mas tu, vai anunciar o Reino de Deus. 'Um outro ainda lhe disse: 'Eu te seguirei, Senhor, mas deixa-me primeiro despedir-me dos meus familiares. 'Jesus, porém, respondeu-lhe: 'Quem põe a mão no arado e olha para trás, não está apto para o Reino de Deus. 'Palavra da Salvação. 
EOD;
        $this->assertEquals("Success", $santoralSection->getLoadStatus());
        $this->assertEquals("Success", $temporalSection->getLoadStatus());
        $this->assertEquals(false, $santoralSection->getSecondReading());

        $firstReading = $temporalSection->getFirstReading();
        $this->assertReading(
            $l1Title,
            $l1Subtitle,
            $l1Intro,
            $l1Text,
            $l1Reference,
            $firstReading
        );
        $salmoReading = $temporalSection->getPsalmReading();
        $this->assertPsalm($salmoTitle, $salmoChorus, $salmoText, $salmoReading);
        $gospelReading = $temporalSection->getGospelReading();
        $this->assertGospelReading(
            $gospelTitle, 
            $gospelSubtitle,
            $gospelIntro, 
            $gospelText,
            $gospelAuthor,
            $gospelReading
        );
        $secondReading = $temporalSection->getSecondReading();
        $this->assertReading(
            $l2Title,
            $l2Subtitle,
            $l2Intro,
            $l2Text,
            $l2Reference,
            $secondReading
        );
    }
}
    

