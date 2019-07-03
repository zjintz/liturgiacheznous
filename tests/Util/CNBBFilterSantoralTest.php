<?php
namespace App\Tests\Util;

use App\Util\CNBBFilter;
use App\Tests\Util\BaseFilterTest;

class CNBBFilterSantoralTest extends BaseFilterTest
{
    
    public function testFilter()
    {
        $iFilter = new CNBBFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleCNBBSantoral.html");
        $liturgyText = $iFilter->filter($data);
        $temporalSection = $liturgyText->getTemporalSection();
        $santoralSection = $liturgyText->getSantoralSection();
        $this->assertEquals("Success", $santoralSection->getLoadStatus());
        $this->assertEquals("Success", $temporalSection->getLoadStatus());
        $this->assertNull($santoralSection->getSecondReading());
        $this->assertNull($temporalSection->getSecondReading());
        $this->assertEquals("Santa Maria Madalena . Memória", $liturgyText->getDayTitle());
        $this->assertTemporal($temporalSection);
        $this->assertSantoral($santoralSection);
  
    }
    
    protected function assertTemporal($temporalSection)
    {
        $l1Title = "1ª Leitura - Ct 3,1-4a";
        $l1Intro = "Encontrei o amor de minha vida.";
        $l1Subtitle= "Leitura do Livro do Cântico dos Cânticos 3,1-4a";
        $salmoTitle = "Salmo - Sl 62(63),2.3-4.5-6.8-9 (R. 2b)";
        $salmoChorus = "R. A minh'alma tem sede de vós, Senhor!";
        $gospelIntro = "Mulher, por que choras? A quem procuras?";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo segundo João 20,1-2.11-18";
        $gospelText = <<<EOD
No primeiro dia da semana, 
Maria Madalena foi ao túmulo de Jesus, 
bem de madrugada, quando ainda estava escuro, 
e viu que a pedra tinha sido retirada do túmulo. 
Então ela saiu correndo 
e foi encontrar Simão Pedro e o outro discípulo, 
aquele que Jesus amava,
e lhes disse: 
"Tiraram o Senhor do túmulo, 
e não sabemos onde o colocaram". 
Maria estava do lado de fora do túmulo, chorando. 
Enquanto chorava,
inclinou-se e olhou para dentro do túmulo.
Viu, então, dois anjos vestidos de branco, 
sentados onde tinha sido posto o corpo de Jesus,
um à cabeceira e outro aos pés. 
Os anjos perguntaram: 
"Mulher, por que choras?" 
Ela respondeu: 
"Levaram o meu Senhor e não sei onde o colocaram".
Tendo dito isto, 
Maria voltou-se para trás e viu Jesus, de pé.
Mas não sabia que era Jesus. 
Jesus perguntou-lhe: 
"Mulher, por que choras? 
A quem procuras?" 
Pensando que era o jardineiro, Maria disse: 
"Senhor, se foste tu que o levaste 
dize-me onde o colocaste, e eu o irei buscar". 
Então Jesus disse: 
"Maria!" 
Ela voltou-se e exclamou, em hebraico: 
"Rabunni" 
 (que quer dizer: Mestre). 
Jesus disse: 
"Não me segures. 
Ainda não subi para junto do Pai. 
Mas vai dizer aos meus irmãos:
subo para junto do meu Pai e vosso Pai, 
meu Deus e vosso Deus".
Então Maria Madalena foi anunciar aos discípulos:
"Eu vi o Senhor!", 
e contou o que Jesus lhe tinha dito. 
Palavra da Salvação.
EOD;
        $l1Text = <<<EOD
Eis o que diz a noiva:
Em meu leito, durante a noite,
busquei o amor de minha vida:
procurei-o, e não o encontrei.
Vou levantar-me e percorrer a cidade,
procurando pelas ruas e praças,
o amor de minha vida:
procurei-o, e não o encontrei.
Encontraram-me os guardas
que faziam a ronda pela cidade.
"Vistes por ventura o amor de minha vida?"
E logo que passei por eles,
encontrei o amor de minha vida.
Palavra do Senhor.
EOD;
        $salmoText = <<<EOD
Sois vós, ó Senhor, o meu Deus! *
Desde a aurora ansioso vos busco!
A minh'alma tem sede de vós, +
minha carne também vos deseja, *
como terra sedenta e sem água!R.
                                        

Venho, assim, contemplar-vos no templo, *
para ver vossa glória e poder.
Vosso amor vale mais do que a vida: *
e por isso meus lábios vos louvam.R.
                                            

Quero, pois vos louvar pela vida, *
e elevar para vós minhas mãos!
A minh'alma será saciada, *
como em grande banquete de festa;
cantará a alegria em meus lábios, *
ao cantar para vós meu louvor!R.
                                                

Para mim fostes sempre um socorro; *
de vossas asas à sombra eu exulto!
Minha alma se agarra em vós; *
com poder vossa mão me sustenta.R.
                                                    
R.
EOD;

        
        $gospelTitle = "Evangelho - Jo 20,1-2.11-18";
        $firstReading = $temporalSection->getFirstReading();
        $this->assertReading(
            $l1Title, $l1Subtitle, $l1Intro, $l1Text, $firstReading
        );
        $salmoReading = $temporalSection->getPsalmReading();
        $this->assertPsalm($salmoTitle, $salmoChorus, $salmoText, $salmoReading);
        $gospelReading = $temporalSection->getGospelReading();
        $this->assertReading(
            $gospelTitle, 
            $gospelSubtitle,
            $gospelIntro, 
            $gospelText,
            $gospelReading
        );    
    }
    
    protected function assertSantoral($section)
    {
        $l1Title = "1ª Leitura - 2Cor 5,14-17";
        $l1Intro = "Agora, já não conhecemos Cristo segundo a carne.";
        $l1Subtitle= "Leitura da Segunda Carta de São Paulo aos Coríntios 5,14-17";
        $l1Text = <<<EOD
Irmãos:
O amor de Cristo nos pressiona,
pois julgamos que um só morreu por todos,
e que, logo, todos morreram.
De fato, Cristo morreu por todos,
para que os vivos não vivam mais para si mesmos,
mas para aquele que por eles morreu e ressuscitou.
Assim, doravante, não conhecemos ninguém
conforme a natureza humana.
E, se uma vez conhecemos Cristo segundo a carne,
agora já não o conhecemos assim.
Portanto, se alguém está em Cristo,
é uma criatura nova.
O mundo velho desapareceu.
Tudo agora é novo.
Palavra do Senhor.
EOD;
        $firstReading = $section->getFirstReading();
        $this->assertReading(
            $l1Title, $l1Subtitle, $l1Intro, $l1Text, $firstReading
        );
    }

}
    

