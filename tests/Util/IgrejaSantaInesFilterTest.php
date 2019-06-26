<?php
namespace App\Tests\Util;

use App\Util\IgrejaSantaInesFilter;
use PHPUnit\Framework\TestCase;

class IgrejaSantaInesFilterTest extends TestCase
{
    public function testFilter()
    {
        $iFilter = new IgrejaSantaInesFilter();
        $example = fopen("./tests/Util/ExampleSantaInes.html", "r") or die("Unable to open file!");
        $data =  fread($example,filesize("./tests/Util/ExampleSantaInes.html"));
        fclose($example);
        $liturgyText = $iFilter->filter($data);
        $this->assertEquals( "25/06/2019 (3ª-FEIRA)", $liturgyText["dayTitle"]);
        $this->assertEquals(
            "1a Leitura - ANO IMPAR Gn 13,2.5-18",
            $liturgyText["l1Title"]);
        
        $this->assertEquals(
            "Salmo - ANO IMPAR Sl 14, 2-3ab. 3cd-4ab. 5 (R. 1b)",
            $liturgyText["salmoTitle"]);

        $this->assertEquals(
            "Evangelho - ANO IMPAR Mt 7,6.12-14",
            $liturgyText["gospelTitle"]);
        
        $l1Text = "Não deve haver discórdia entre nós pois somos irmãos.
Leitura do Livro do Gênesis 13, 2. 5-18
Abrão era muito rico em rebanhos, prata e ouro. Ló, que acompanhava Abrão, também tinha ovelhas, gado e tendas. A região já não bastava para os dois, pois seus rebanhos eram demasiado numerosos, para poderem morar juntos. Surgiram discórdias entre os pastores que cuidavam da criação de Abrão, e os pastores de Ló. Naquele tempo, os cananeus e os fereseus ainda habitavam naquela terra. Abrão disse a Ló: 'Não deve haver discórdia entre nós e entre os nossos pastores, pois somos irmãos. Estás vendo toda esta terra diante de ti? Pois bem, peço-te, separa-te de mim. Se fores para a esquerda, eu irei para a direita; Se fores para a direita, eu irei para a esquerda'. Levantando os olhos, Ló viu que toda a região em torno do Jordão era por toda a parte irrigada - isso antes que o Senhor destruísse Sodoma e Gomorra -, era como um jardim do Senhor e como o Egito, até a altura de Segor. Ló escolheu, então, para si a região em torno do Jordão, e foi para oriente. Foi assim que os dois se separaram um do outro. Abrão habitou na terra de Canaã, enquanto que Ló se estabeleceu nas cidades próximas do Jordão, e armou suas tendas até Sodoma. Ora, os habitantes de Sodoma eram péssimos, e grandes pecadores diante do Senhor. E o Senhor disse a Abrão, depois que Ló se separou dele: 'Ergue os olhos e, do lugar onde estás, olha para o norte e para o sul, para o oriente e para o ocidente: toda essa terra que estás vendo, eu a darei a ti e à tua descendência para sempre. Tornarei tua descendência tão numerosa como o pó da terra. Se alguém puder contar os grãos do pó da terra, então poderá contar a tua descendência. Levanta-te e percorre este país de ponta a ponta, porque é a ti que o darei. Tendo desarmado suas tendas, Abrão foi morar junto ao Carvalho de Mambré, que está em Hebron, e ali construiu um altar ao Senhor. Palavra do Senhor. ";
        //        $liturgyText["dayTitle"]
        /*        $this->assertEquals(
            $l1Text,
            $liturgyText["l1Text"]);*/
    }
}
