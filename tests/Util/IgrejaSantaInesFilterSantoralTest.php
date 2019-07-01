<?php
namespace App\Tests\Util;

use App\Util\IgrejaSantaInesFilter;
use App\Tests\Util\BaseFilterTest;

class IgrejaSantaInesFilterSantoralTest extends BaseFilterTest
{
    
    public function testFilter()
    {
        $iFilter = new IgrejaSantaInesFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleSantaInesSantoral.html");
        $liturgyText = $iFilter->filter($data);
        $temporalText = $liturgyText["temporal"];
        $santoralText = $liturgyText["santoral"];
        $this->assertEquals("Success", $temporalText["status"]);
        $this->assertEquals("Success", $santoralText["status"]);
        $this->assertEquals(false, $temporalText["hasL2"]);
        $this->assertEquals(false, $santoralText["hasL2"]);

        $this->assertEquals("27/06/2019 (5ª-FEIRA)", $liturgyText["dayTitle"]);
        $this->assertTemporal($temporalText);
        $this->assertSantoral($santoralText);

    }
    
    protected function assertTemporal($temporalText)
    {
        $this->assertEquals(
            "1a Leitura - ANO IMPAR Gn 16,1-12.15-16",
            $temporalText["l1Title"]
        );
        
        $this->assertEquals(
            "Salmo - ANO IMPAR Sl 105, 1-2. 3-4a. 4b-5 (R. 1a)",
            $temporalText["salmoTitle"]
        );

        $this->assertEquals(
            "Evangelho - ANO IMPAR Mt 7,21-29",
            $temporalText["gospelTitle"]
        );

        
        $l1Intro = "Agar deu à luz o filho de Abrão, a quem ele pôs o nome de Ismael.";
        $l1Subtitle= "Leitura do Livro do Gênesis 16, 1-12. 15-16";
        $salmoChorus = "Dai graças ao Senhor, porque ele é bom.";
        $gospelIntro = "A casa construída sobre a rocha ea casa construída sobre a areia.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo segundo São Mateus 7, 21-29";
        $gospelText = <<<EOD
Naquele tempo, disse Jesus aos seus discípulos: Nem todo aquele que me diz: 'Senhor, Senhor', entrará no Reino dos Céus, mas o que põe em prática a vontade de meu Pai que está nos céus. Naquele dia, muitos vão me dizer: 'Senhor, Senhor, não foi em teu nome que profetizamos? Não foi em teu nome que expulsamos demônios? E não foi em teu nome que fizemos muitos milagres?'Então eu lhes direi publicamente: 'Jamais vos conheci. Afastai-vos de mim, vós que praticais o mal. Portanto, quem ouve estas minhas palavras e as põe em prática, é como um homem prudente, que construiu sua casa sobre a rocha. Caiu a chuva, vieram as enchentes, os ventos deram contra a casa, mas a casa não caiu, porque estava construída sobre a rocha. Por outro lado, quem ouve estas minhas palavras e não as põe em prática, é como um homem sem juízo, que construiu sua casa sobre a areia. Caiu a chuva, vieram as enchentes, os ventos sopraram e deram contra a casa, e a casa caiu, e sua ruína foi completa!'Quando Jesus acabou de dizer estas palavras, as multidões ficaram admiradas com seu ensinamento. De fato, ele as ensinava como quem tem autoridade e não como os mestres da lei. Palavra da Salvação. 
EOD;
    
        $this->assertEquals($l1Subtitle, $temporalText["l1Subtitle"]);
        $this->assertEquals($l1Intro, $temporalText["l1Intro"]);
        $this->assertEquals($salmoChorus, $temporalText["salmoChorus"]);
        $this->assertEquals($gospelSubtitle, $temporalText["gospelSubtitle"]);
        $this->assertEquals($gospelIntro, $temporalText["gospelIntro"]);
        $this->assertEquals($gospelText, $temporalText["gospelText"]);
    
    }

    protected function assertSantoral($santoralText)
    {
        $this->assertEquals(
            "1a Leitura - SANTORAL 2Tm 4,1-5",
            $santoralText["l1Title"]
        );
        
        $this->assertEquals(
            "Salmo - SANTORAL Sl 88(89),2-3.4-5.21-22.25 e 27 (R. cf. 2a)",
            $santoralText["salmoTitle"]
        );

        $this->assertEquals(
            "Evangelho - SANTORAL Mt 5,13-19",
            $santoralText["gospelTitle"]
        );
        $l1Intro = "Desempenha o teu serviço de pregador do evangelho, e cumpre com perfeição o teu ministério.";
        $l1Subtitle= "Leitura da Segunda Carta de São Paulo a Timóteo 4, 1-5";
        $salmoChorus = "Ó Senhor, eu cantarei eternamente vosso amor.";
        $gospelIntro = "Vós sois a luz do mundo.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo segundo Mateus 5, 13-19";
        $gospelText = <<<EOD
Naquele tempo, disse Jesus aos seus discípulos: 'Vós sois o sal da terra. Ora, se o sal se tornar insosso, com que salgaremos? Ele não servirá para mais nada, senão para ser jogado fora e ser pisado pelos homens. Vós sois a luz do mundo. Não pode ficar escondida uma cidade construída sobre um monte. Ninguém acende uma lâmpada e a coloca debaixo de uma vasilha, mas sim num candeeiro, onde ela brilha para todos os que estão em casa. Assim também brilhe a vossa luz diante dos homens, para que vejam as vossas boas obras e louvem o vosso Pai que está nos céus. Não penseis que vim abolir a Lei e os Profetas. Não vim para abolir, mas para dar-lhes pleno cumprimento. Em verdade, eu vos digo: antes que o céu e a terra deixem de existir, nem uma só letra ou vírgula serão tiradas da Lei, sem que tudo se cumpra. Portanto, quem desobedecer a um só destes mandamentos, por menor que seja, e ensinar os outros a fazerem o mesmo, será considerado o menor no Reino dos Céus. Porém, quem os praticar e ensinar será considerado grande no Reino dos Céus'. Palavra da Salvação. 
EOD;
    
        $this->assertEquals($l1Subtitle, $santoralText["l1Subtitle"]);
        $this->assertEquals($l1Intro, $santoralText["l1Intro"]);
        $this->assertEquals($salmoChorus, $santoralText["salmoChorus"]);
        $this->assertEquals($gospelSubtitle, $santoralText["gospelSubtitle"]);
        $this->assertEquals($gospelIntro, $santoralText["gospelIntro"]);
        $this->assertEquals($gospelText, $santoralText["gospelText"]);
    
    }
        
}
    

