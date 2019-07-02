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
        $temporalSection = $liturgyText->getTemporalSection();
        $santoralSection = $liturgyText->getSantoralSection();
        $this->assertEquals("Success", $santoralSection->getLoadStatus());
        $this->assertEquals("Success", $temporalSection->getLoadStatus());
        $this->assertNull($santoralSection->getSecondReading());
        $this->assertNull($temporalSection->getSecondReading());
        $this->assertEquals("27/06/2019 (5ª-FEIRA)", $liturgyText->getDayTitle());
        $this->assertTemporal($temporalSection);
        $this->assertSantoral($santoralSection);

    }
    
    protected function assertTemporal($temporalSection)
    {   
        $l1Intro = "Agar deu à luz o filho de Abrão, a quem ele pôs o nome de Ismael.";
        $l1Subtitle= "Leitura do Livro do Gênesis 16, 1-12. 15-16";
        $salmoChorus = "Dai graças ao Senhor, porque ele é bom.";
        $gospelIntro = "A casa construída sobre a rocha ea casa construída sobre a areia.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo segundo São Mateus 7, 21-29";
        $gospelText = <<<EOD
Naquele tempo, disse Jesus aos seus discípulos: Nem todo aquele que me diz: 'Senhor, Senhor', entrará no Reino dos Céus, mas o que põe em prática a vontade de meu Pai que está nos céus. Naquele dia, muitos vão me dizer: 'Senhor, Senhor, não foi em teu nome que profetizamos? Não foi em teu nome que expulsamos demônios? E não foi em teu nome que fizemos muitos milagres?'Então eu lhes direi publicamente: 'Jamais vos conheci. Afastai-vos de mim, vós que praticais o mal. Portanto, quem ouve estas minhas palavras e as põe em prática, é como um homem prudente, que construiu sua casa sobre a rocha. Caiu a chuva, vieram as enchentes, os ventos deram contra a casa, mas a casa não caiu, porque estava construída sobre a rocha. Por outro lado, quem ouve estas minhas palavras e não as põe em prática, é como um homem sem juízo, que construiu sua casa sobre a areia. Caiu a chuva, vieram as enchentes, os ventos sopraram e deram contra a casa, e a casa caiu, e sua ruína foi completa!'Quando Jesus acabou de dizer estas palavras, as multidões ficaram admiradas com seu ensinamento. De fato, ele as ensinava como quem tem autoridade e não como os mestres da lei. Palavra da Salvação. 
EOD;
        $l1Text = <<<EOD
Sarai, a mulher de Abrão, não lhe dera filhos. Mas, tendo uma escrava egípcia, chamada Agar, Sarai disse a Abrão: 'Eis que o Senhor me fez estéril. Une-te, pois, à minha escrava, para ver se, por ela, posso ter filhos'. Abrão atendeu ao pedido de Sarai. Depois de Abrão ter morado dez anos em Canaã, Sarai, sua esposa, tomou sua escrava egípcia, Agar, e deu-a como mulher ao seu marido Abrão. Abrão uniu-se a Agar e ela concebeu. Percebendo-se grávida, começou a olhar com desprezo a sua senhora. Sarai disse a Abrão: 'Tu és responsável pela injúria que estou sofrendo. Fui eu mesma que coloquei minha escrava em teus braços: e ela, apenas ficou grávida, pôs-se a desprezar-me. O Senhor será juiz entre mim e ti'. Abrão respondeu a Sarai: 'Olha, a escrava é tua; faze dela o que bem estenderes'. E Sarai maltratou-a tanto que ela fugiu. Um anjo do Senhor, encontrando-a junto à fonte do deserto, no caminho de Sur, disse-lhe: 'Agar, escrava de Sarai, de onde vens e para onde vais?' Ela respondeu: 'Estou fugindo de Sarai, minha senhora'. E o anjo do Senhor lhe disse: 'Volta para a tua senhora e sê submissa a ela'. E acrescentou: 'Multiplicarei a tua descendência de tal forma, que não se poderá contar'. Disse, ainda, o anjo do Senhor: 'Olha, estás grávida e darás à luz um filho e o chamarás Ismael, porque o Senhor te ouviu na tua aflição. Ele será indomável como um jumento selvagem, sua mão se levantará contra todos, e a mão de todos contra ele. E ele viverá separado de todos os seus irmãos'. Agar deu à luz o filho de Abrão; e ele pôs o nome de Ismael ao filho que Agar lhe deu. Abrão tinha oitenta e seis anos, quando Agar deu à luz Ismael. Palavra do Senhor. 
EOD;
        $salmoText = <<<EOD
Dai graças ao Senhor, porque ele é bom, / porque eterna é a sua misericórdia!Quem contará os grandes feitos do Senhor? / Quem cantará todo o louvor que ele merece?
R.
 Felizes os que guardam seus preceitos / e praticam a justiça em todo o tempo!Lembrai-vos, ó Senhor, de mim, lembrai-vos, / pelo amor que demonstrais ao vosso povo!
R.
 Visitai-me com a vossa salvação, para que eu veja o bem-estar do vosso povo, / e exulte na alegria dos eleitos, / e me glorie com os que são vossa herança.
R.
EOD;
        $l1Title = "1a Leitura - ANO IMPAR Gn 16,1-12.15-16";
        $salmoTitle = "Salmo - ANO IMPAR Sl 105, 1-2. 3-4a. 4b-5 (R. 1a)";
        $gospelTitle = "Evangelho - ANO IMPAR Mt 7,21-29";
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

    protected function assertSantoral($santoralSection)
    {
        $l1Title = "1a Leitura - SANTORAL 2Tm 4,1-5";
        $salmoTitle = "Salmo - SANTORAL Sl 88(89),2-3.4-5.21-22.25 e 27 (R. cf. 2a)";
        $gospelTitle = "Evangelho - SANTORAL Mt 5,13-19";

        $l1Intro = "Desempenha o teu serviço de pregador do evangelho, e cumpre com perfeição o teu ministério.";
        $l1Subtitle= "Leitura da Segunda Carta de São Paulo a Timóteo 4, 1-5";
        $salmoChorus = "Ó Senhor, eu cantarei eternamente vosso amor.";
        $gospelIntro = "Vós sois a luz do mundo.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo segundo Mateus 5, 13-19";
        $gospelText = <<<EOD
Naquele tempo, disse Jesus aos seus discípulos: 'Vós sois o sal da terra. Ora, se o sal se tornar insosso, com que salgaremos? Ele não servirá para mais nada, senão para ser jogado fora e ser pisado pelos homens. Vós sois a luz do mundo. Não pode ficar escondida uma cidade construída sobre um monte. Ninguém acende uma lâmpada e a coloca debaixo de uma vasilha, mas sim num candeeiro, onde ela brilha para todos os que estão em casa. Assim também brilhe a vossa luz diante dos homens, para que vejam as vossas boas obras e louvem o vosso Pai que está nos céus. Não penseis que vim abolir a Lei e os Profetas. Não vim para abolir, mas para dar-lhes pleno cumprimento. Em verdade, eu vos digo: antes que o céu e a terra deixem de existir, nem uma só letra ou vírgula serão tiradas da Lei, sem que tudo se cumpra. Portanto, quem desobedecer a um só destes mandamentos, por menor que seja, e ensinar os outros a fazerem o mesmo, será considerado o menor no Reino dos Céus. Porém, quem os praticar e ensinar será considerado grande no Reino dos Céus'. Palavra da Salvação. 
EOD;
        $l1Text = <<<EOD
Caríssimo: Diante de Deus e de Cristo Jesus, que há de vir a julgar os vivos e os mortos, e em virtude da sua manifestação gloriosa e do seu Reino, eu te peço com insistência: proclama a palavra, insiste oportuna ou importunamente, argumenta, repreende, aconselha, com toda a paciência e doutrina. Pois vai chegar o tempo em que não suportarão a só doutrina, mas, com o prurido da curiosidade nos ouvidos, se rodearão de mestres ao sabor de seus próprios caprichos. E assim, deixando de ouvir a verdade, se desviarão para as fábulas. Tu, porém, mostra vigilância em tudo, suporta o sofrimento, desempenha o teu serviço de pregador do evangelho, cumpre com perfeição o teu ministério. Sê sóbrio. Palavra do Senhor. 
EOD;
        $salmoText = <<<EOD
Ó Senhor, eu cantarei eternamente o vosso amor, / de geração em geração eu cantarei vossa verdade!Porque dissestes: 'O amor é garantido para sempre!' / E a vossa lealdade é tão firme como os céus.
R.
'Eu firmei uma Aliança com meu servo, meu eleito, / e eu fiz um juramento a Davi, meu servidor. Para sempre, no teu trono, firmarei tua linhagem, / de geração em geração garantirei o teu reinado!'
R.
 Encontrei e escolhi a Davi, meu servidor, / e o ungi, para ser rei, com meu óleo consagrado. Estará sempre com ele minha mão onipotente, / e meu braço poderoso há de ser a sua força.
R.
Minha verdade e meu amor estarão sempre com ele, / sua força e seu poder por meu nome crescerão. Ele, então, me invocará: `Ó Senhor, vós sois meu Pai, / sois meu Deus, sois meu Rochedo onde encontro a salvação'!
R.
EOD;
        $firstReading = $santoralSection->getFirstReading();
        $this->assertReading(
            $l1Title, $l1Subtitle, $l1Intro, $l1Text, $firstReading
        );
        $salmoReading = $santoralSection->getPsalmReading();
        $this->assertPsalm($salmoTitle, $salmoChorus, $salmoText, $salmoReading);
        $gospelReading = $santoralSection->getGospelReading();
        $this->assertReading(
            $gospelTitle, 
            $gospelSubtitle,
            $gospelIntro, 
            $gospelText,
            $gospelReading
        );    
    
    }
        
}
    

