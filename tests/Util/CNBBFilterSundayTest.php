<?php
namespace App\Tests\Util;

use App\Util\CNBBFilter;
use App\Tests\Util\BaseFilterTest;

class CNBBFilterSundayTest extends BaseFilterTest
{
    
    public function testFilter()
    {
        $iFilter = new CNBBFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleCNBBSunday.html");
        $liturgyText = $iFilter->filter($data, "2019-06-30");
        $temporalSection = $liturgyText->getTemporalSection();
        $santoralSection = $liturgyText->getSantoralSection();
        $this->assertEquals("Not_Found", $santoralSection->getLoadStatus());
        $this->assertEquals("Success", $temporalSection->getLoadStatus());
        $this->assertEquals("São Pedro e São Paulo, Apóstolos . Solenidade", $liturgyText->getDayTitle());
        $this->assertEquals(new \DateTime("2019-06-30"), $liturgyText->getDate());
        $this->assertTemporal($temporalSection);
  
    }

    /**
     * Additional test because this case is failing.
     */
    public function testFilterDec01()
    {
        $iFilter = new CNBBFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleCNBB-2019-12-01.html");
        $liturgyText = $iFilter->filter($data, "2019-12-01");
        $temporalSection = $liturgyText->getTemporalSection();
        $santoralSection = $liturgyText->getSantoralSection();
        $this->assertEquals("Success", $liturgyText->getLoadStatus());
        $this->assertEquals("Not_Found", $santoralSection->getLoadStatus());
        $this->assertEquals("Success", $temporalSection->getLoadStatus());
    }
    
    protected function assertTemporal($temporalSection)
    {
        $l1Title = "1ª Leitura - At 12,1-11";
        $l1Reference = "At 12,1-11";
        $l1Intro = "Agora sei que o Senhor enviou o seu anjo para me libertar do poder de Herodes.";
        $l1Subtitle= "Leitura dos Atos dos Apóstolos 12,1-11";
        $salmoTitle = "Salmo - Sl 33(34),2-3.4-5.6-7.8-9 (R. 5)";
        $salmoChorus = "De todos os temores me livrou o Senhor Deus.";
        $gospelIntro = "Tu és Pedro e eu te darei as chaves do Reino dos Céus.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo segundo Mateus 16,13-19";
        $gospelAuthor = "Mateus";
        $gospelText = <<<EOD
Naquele tempo:
Jesus foi à região de Cesaréia de Filipe
e ali perguntou aos seus discípulos:
"Quem dizem os homens ser o Filho do Homem?"
Eles responderam:
"Alguns dizem que é João Batista; outros que é Elias;
Outros ainda, que é Jeremias ou algum dos profetas".
Então Jesus lhes perguntou:
"E vós, quem dizeis que eu sou?"
Simão Pedro respondeu:
"Tu és o Messias, o Filho do Deus vivo".
Respondendo, Jesus lhe disse:
"Feliz es tu, Simão, filho de Jonas,
porque não foi um ser humano que te revelou isso,
mas o meu Pai que está no céu.
Por isso eu te digo que tu és Pedro,
e sobre esta pedra construirei a minha Igreja,
e o poder do inferno nunca poderá vencê-la.
Eu te darei as chaves do Reino dos Céus:
tudo o que tu ligares na terra será ligado nos céus;
tudo o que tu desligares na terra 
será desligado nos céus".
EOD;
        $l1Text = <<<EOD
Naqueles dias,
o rei Herodes
prendeu alguns membros da Igreja, para torturá-los.
Mandou matar à espada Tiago, irmão de João.
E, vendo que isso agradava aos judeus,
mandou também prender a Pedro.
Eram os dias dos Pães ázimos.
Depois de prender Pedro,
Herodes colocou-o na prisão,
guardado por quatro grupos de soldados,
com quatro soldados cada um.
Herodes tinha a intenção de apresentá-lo ao povo,
depois da festa da Páscoa.
Enquanto Pedro era mantido na prisão,
a Igreja rezava continuamente a Deus por ele.
Herodes estava para apresentá-lo.
Naquela mesma noite,
Pedro dormia entre dois soldados,
preso com duas correntes;
e os guardas vigiavam a porta da prisão.
Eis que apareceu o anjo do Senhor
e uma luz iluminou a cela.
O anjo tocou o ombro de Pedro, acordou-o e disse:
"Levanta-te depressa!"
As corrrentes caíram-lhe das mãos.
O anjo continuou:
"Coloca o cinto e calça tuas sandálias!"
Pedro obedeceu e o anjo lhe disse:
"Põe tua capa e vem comigo!"
Pedro acompanhou-o, e não sabia que era realidade
o que estava acontecendo por meio do anjo,
pois pensava que aquilo era uma visão.
Depois de passarem pela primeira e segunda guarda,
chegaram ao portão de ferro que dava para a cidade.
O portão abriu-se sozinho. Eles saíram, 
caminharam por uma rua e logo depois o anjo o deixou.
Então Pedro caiu em si e disse:
"Agora sei, de fato, que o Senhor enviou o seu anjo
para me libertar do poder de Herodes
e de tudo o que o povo judeu esperava!"

EOD;
        $salmoText = <<<EOD
Bendirei o Senhor Deus em todo o tempo, *
seu louvor estará sempre em minha boca.
Minha alma se gloria no Senhor; *
que ouçam os humildes e se alegrem! R.
 R.
Comigo engrandecei ao Senhor Deus, *
exaltemos todos juntos o seu nome!
Todas as vezes que o busquei, ele me ouviu, *
e de todos os temores me livrou. R.
 R.
Contemplai a sua face e alegrai-vos, *
e vosso rosto não se cubra de vergonha!
Este infeliz gritou a Deus, e foi ouvido, *
e o Senhor o libertou de toda angústia. R.
 R.
O anjo do Senhor vem acampar *
ao redor dos que o temem, e os salva.
Provai e vede quão suave é o Senhor! *
Feliz o homem que tem nele o seu refúgio! R.
 R.
EOD;

        $l2Title = "2ª Leitura - 2Tm 4,6-8.17-18";
        $l2Reference = "2Tm 4,6-8.17-18";
        $l2Intro = "Agora está reservada para mim a coroa da justiça.";
        $l2Subtitle = "Leitura da Segunda Carta de São Paulo a Timóteo 4,6-8.17-18";
        $l2Text = <<<EOD
Caríssimo:
Quanto a mim,
eu já estou para ser derramado em sacrifício;
aproxima-se o momento de minha partida.
Combati o bom combate,
completei a corrida, guardei a fé.
Agora está reservada para mim a coroa da justiça,
que o Senhor, justo juiz, me dará naquele dia;
e não somente a mim,
mas também a todos que esperam com amor
a sua manifestação gloriosa.
Mas o Senhor esteve a meu lado e me deu forças;
ele fez com que a mensagem
fosse anunciada por mim integralmente,
e ouvida por todas as nações;
e eu fui libertado da boca do leão.
O Senhor me libertará de todo mal
e me salvará para o seu Reino celeste.
A ele a glória, pelos séculos dos séculos! Amém.

EOD;
        $gospelTitle = "Evangelho - Mt 16,13-19";
        $firstReading = $temporalSection->getFirstReading();
        $this->assertReading(
            $l1Title, $l1Subtitle, $l1Intro, $l1Text,
            $l1Reference,
            $firstReading
        );
        $secondReading = $temporalSection->getSecondReading();
        $this->assertReading(
            $l2Title, $l2Subtitle, $l2Intro, $l2Text, $l2Reference, $secondReading
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
    }

}
