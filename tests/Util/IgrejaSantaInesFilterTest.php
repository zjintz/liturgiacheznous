<?php
namespace App\Tests\Util;

use App\Util\IgrejaSantaInesFilter;
use PHPUnit\Framework\TestCase;

class IgrejaSantaInesFilterTest extends TestCase
{
    protected function readExample($path)
    {
        $example = fopen($path, "r") or die("Unable to open file!");
        $data =  fread($example, filesize($path));
        fclose($example);
        return $data;
    }
    public function testFilterNotFound()
    {
        $iFilter = new IgrejaSantaInesFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleSantaInes_NOT_FOUND.html");
        $liturgyText = $iFilter->filter($data);
        $this->assertEquals("Not_Found", $liturgyText["status"]);
    }
    public function testFilter()
    {
        $iFilter = new IgrejaSantaInesFilter();
        $data = $this->readExample("./tests/Util/examples/ExampleSantaInes.html");
        $liturgyText = $iFilter->filter($data);
        $temporalText = $liturgyText["temporal"];
        $santoralText = $liturgyText["santoral"];
        $this->assertEquals("Success", $temporalText["status"]);
        $this->assertEquals("Not_Found", $santoralText["status"]);
        $this->assertEquals(false, $temporalText["hasL2"]);
        $this->assertEquals("25/06/2019 (3ª-FEIRA)", $liturgyText["dayTitle"]);
        $this->assertEquals(
            "1a Leitura - ANO IMPAR Gn 13,2.5-18",
            $temporalText["l1Title"]);
        
        $this->assertEquals(
            "Salmo - ANO IMPAR Sl 14, 2-3ab. 3cd-4ab. 5 (R. 1b)",
            $temporalText["salmoTitle"]
        );

        $this->assertEquals(
            "Evangelho - ANO IMPAR Mt 7,6.12-14",
            $temporalText["gospelTitle"]
        );
        
        $l1Intro = "Não deve haver discórdia entre nós pois somos irmãos.";
        $l1Subtitle= "Leitura do Livro do Gênesis 13, 2. 5-18";
        $l1Text = "Abrão era muito rico em rebanhos, prata e ouro. Ló, que acompanhava Abrão, também tinha ovelhas, gado e tendas. A região já não bastava para os dois, pois seus rebanhos eram demasiado numerosos, para poderem morar juntos. Surgiram discórdias entre os pastores que cuidavam da criação de Abrão, e os pastores de Ló. Naquele tempo, os cananeus e os fereseus ainda habitavam naquela terra. Abrão disse a Ló: 'Não deve haver discórdia entre nós e entre os nossos pastores, pois somos irmãos. Estás vendo toda esta terra diante de ti? Pois bem, peço-te, separa-te de mim. Se fores para a esquerda, eu irei para a direita; Se fores para a direita, eu irei para a esquerda'. Levantando os olhos, Ló viu que toda a região em torno do Jordão era por toda a parte irrigada - isso antes que o Senhor destruísse Sodoma e Gomorra -, era como um jardim do Senhor e como o Egito, até a altura de Segor. Ló escolheu, então, para si a região em torno do Jordão, e foi para oriente. Foi assim que os dois se separaram um do outro. Abrão habitou na terra de Canaã, enquanto que Ló se estabeleceu nas cidades próximas do Jordão, e armou suas tendas até Sodoma. Ora, os habitantes de Sodoma eram péssimos, e grandes pecadores diante do Senhor. E o Senhor disse a Abrão, depois que Ló se separou dele: 'Ergue os olhos e, do lugar onde estás, olha para o norte e para o sul, para o oriente e para o ocidente: toda essa terra que estás vendo, eu a darei a ti e à tua descendência para sempre. Tornarei tua descendência tão numerosa como o pó da terra. Se alguém puder contar os grãos do pó da terra, então poderá contar a tua descendência. Levanta-te e percorre este país de ponta a ponta, porque é a ti que o darei. Tendo desarmado suas tendas, Abrão foi morar junto ao Carvalho de Mambré, que está em Hebron, e ali construiu um altar ao Senhor. Palavra do Senhor. ";

        $salmoChorus = "Senhor, quem morará em vosso Monte Santo?";
        $salmoText = <<<EOD
É aquele que caminha sem pecado / e pratica a justiça fielmente; que pensa a verdade no seu íntimo e não solta em calúnias sua língua.
R.
Que em nada prejudica o seu irmão, nem cobre de insultos seu vizinho; que não dá valor algum ao homem ímpio, mas honra os que respeitam o Senhor.
R.
não empresta o seu dinheiro com usura, / nem se deixa subornar contra o inocente. / Jamais vacilará quem vive assim!
R.
EOD;
        $gospelIntro = "Tudo quanto quereis que os outros vos façam, fazei também a eles.";
        $gospelSubtitle = "+ Proclamação do Evangelho de Jesus Cristo Segundo São Mateus 7, 6. 12-14";
        $gospelText = <<<EOD
Naquele tempo, disse Jesus aos seus discípulos: Não deis aos cães as coisas santas, nem atireis vossas pérolas aos porcos; para que eles não as pisem com os pés e, voltando-se contra vós, vos despedacem. Tudo quanto quereis que os outros vos façam, fazei também a eles. Nisto consiste a Lei e os Profetas. Entrai pela porta estreita, porque larga é a porta e espaçoso é o caminho que leva à perdição, e muitos são os que entram por ele!Como é estreita a porta e apertado o caminho que leva à vida! E são poucos os que o encontram!Palavra da Salvação. 
EOD;
    
        $this->assertEquals($l1Subtitle, $temporalText["l1Subtitle"]);
        $this->assertEquals($l1Intro, $temporalText["l1Intro"]);
        $this->assertEquals($l1Text, $temporalText["l1Text"]);
        $this->assertEquals($salmoChorus, $temporalText["salmoChorus"]);
        $this->assertEquals($salmoText, $temporalText["salmoText"]);
        $this->assertEquals($gospelSubtitle, $temporalText["gospelSubtitle"]);
        $this->assertEquals($gospelIntro, $temporalText["gospelIntro"]);
        $this->assertEquals($gospelText, $temporalText["gospelText"]);
    }
}
