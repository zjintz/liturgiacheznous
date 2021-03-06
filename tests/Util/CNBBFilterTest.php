<?php
namespace App\Tests\Util;

use App\Tests\Util\BaseFilterTest;
use App\Util\CNBBFilter;
use App\Entity\LiturgyText;

class CNBBFilterTest extends BaseFilterTest
{

    public function testFilterFalseData()
    {
        $iFilter = new CNBBFilter();
        $liturgyText = $iFilter->filter(false, "");
        $this->assertEquals("Error: No_Data_Found", $liturgyText->getLoadStatus());
    }
    
    public function testFilterNotFound()
    {
        $iFilter = new CNBBFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleCNBB_NOT_FOUND.html");
        $liturgyText = $iFilter->filter($data, "");
        $this->assertEquals("Error: Invalid_Date", $liturgyText->getLoadStatus());
    }

    public function testFilter2Chorus()
    {
        $iFilter = new CNBBFilter();
        $data = $this->readExample(
            "./tests/Util/examples/ExampleCNBB_2CHORUS.html"
        );
        $liturgyText = $iFilter->filter($data, "");
        $temporalSection = $liturgyText->getTemporalSection();

        $salmoTitle = "Salmo - Sl 67, 2-3. 4-5ac. 6-7ab (R. 33a)";
        $salmoChorus = <<<EOD
Reinos da terra, cantai ao Senhor.
Ou: Aleluia, Aleluia, Aleluia
EOD;
        $reading = $temporalSection->getPsalmReading();
        $this->assertEquals($salmoTitle, $reading->getTitle());
        $this->assertEquals($salmoChorus, $reading->getChorus());
    }
    
    public function testFilter()
    {
        $iFilter = new CNBBFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleCNBB.html");

        $liturgyText = $iFilter->filter($data, "2019-06-25");
        $temporalSection = $liturgyText->getTemporalSection();
        $santoralSection = $liturgyText->getSantoralSection();
        $this->assertEquals("Not_Found", $santoralSection->getLoadStatus());
        $this->assertEquals("Success", $temporalSection->getLoadStatus());
        $this->assertEquals(false, $temporalSection->getSecondReading());
        $this->assertEquals(
            "3ª-feira da 12ª Semana Do Tempo Comum",
            $liturgyText->getDayTitle()
        );
        $this->assertEquals(new \DateTime("2019-06-25"), $liturgyText->getDate());
        $l1Intro = "Não deve haver discórdia entre nós pois somos irmãos.";
        $l1Title =  "1ª Leitura - Gn 13,2.5-18";
        $l1Reference = "Gn 13,2.5-18";
        $l1Subtitle= "Leitura do Livro do Gênesis 13,2.5-18";
        $l1Text = <<<EOD
Abrão era muito rico em rebanhos, prata e ouro.
Ló, que acompanhava Abrão, também tinha ovelhas, gado e tendas.
A região já não bastava para os dois,
pois seus rebanhos eram demasiado numerosos,
para poderem morar juntos.
Surgiram discórdias entre os pastores
que cuidavam da criação de Abrão, e os pastores de Ló.
Naquele tempo, os cananeus e os fereseus ainda
habitavam naquela terra.
Abrão disse a Ló:
'Não deve haver discórdia entre nós
e entre os nossos pastores,
pois somos irmãos.
Estás vendo toda esta terra diante de ti?
Pois bem, peço-te, separa-te de mim.
Se fores para a esquerda, eu irei para a direita;
Se fores para a direita, eu irei para a esquerda'.
Levantando os olhos,
Ló viu que toda a região em torno do Jordão
era por toda a parte irrigada
- isso antes que o Senhor destruísse Sodoma e Gomorra -,
era como um jardim do Senhor
e como o Egito,
até a altura de Segor.
Ló escolheu, então, para si a região em torno do Jordão,
e foi para oriente.
Foi assim que os dois se separaram um do outro.
Abrão habitou na terra de Canaã,
enquanto que Ló se estabeleceu nas cidades próximas do Jordão,
e armou suas tendas até Sodoma.
Ora, os habitantes de Sodoma eram péssimos,
e grandes pecadores diante do Senhor.
E o Senhor disse a Abrão,
depois que Ló se separou dele:
'Ergue os olhos e, do lugar onde estás,
olha para o norte e para o sul,
para o oriente e para o ocidente:
toda essa terra que estás vendo, eu a darei
a ti e à tua descendência para sempre.
Tornarei tua descendência tão numerosa
como o pó da terra.
Se alguém puder contar os grãos do pó da terra,
então poderá contar a tua descendência.
Levanta-te e percorre este país de ponta a ponta,
porque é a ti que o darei.
Tendo desarmado suas tendas,
Abrão foi morar junto ao Carvalho de Mambré, que está em Hebron,
e ali construiu um altar ao Senhor.

EOD;
        
        $salmoChorus = "Senhor, quem morará em vosso Monte Santo?";
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

        $gospelIntro = "Tudo quanto quereis que os outros vos façam, fazei também a eles.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo Segundo São Mateus 7,6.12-14";
        $gospelAuthor = "Mateus";
        $gospelText = <<<EOD
Naquele tempo, disse Jesus aos seus discípulos:
Não deis aos cães as coisas santas,
nem atireis vossas pérolas aos porcos;
para que eles não as pisem com os pés
e, voltando-se contra vós, vos despedacem.
Tudo quanto quereis que os outros vos façam,
fazei também a eles.
Nisto consiste a Lei e os Profetas.
Entrai pela porta estreita,
porque larga é a porta
e espaçoso é o caminho que leva à perdição,
e muitos são os que entram por ele!
Como é estreita a porta
e apertado o caminho que leva à vida!
E são poucos os que o encontram!
EOD;

        $this->assertEquals(
            "3ª-feira da 12ª Semana Do Tempo Comum",
            $liturgyText->getDayTitle()
        );
        $firstReading = $temporalSection->getFirstReading();
        $this->assertReading(
            $l1Title, $l1Subtitle, $l1Intro, $l1Text, $l1Reference, $firstReading
        );
        $salmoTitle = "Salmo - Sl 14, 2-3ab. 3cd-4ab. 5 (R. 1b)";
        $salmoReference = "Sl 14, 2-3ab. 3cd-4ab. 5 (R. 1b)";
        $salmoReading = $temporalSection->getPsalmReading();
        $this->assertEquals($salmoReference, $salmoReading->getReference());
        $this->assertPsalm($salmoTitle, $salmoChorus, $salmoText, $salmoReading);
        $gospelTitle = "Evangelho - Mt 7,6.12-14";
        $gospelReference = "Mt 7,6.12-14";
        $gospelReading = $temporalSection->getGospelReading();
        $this->assertEquals($gospelReference, $gospelReading->getReference());
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
