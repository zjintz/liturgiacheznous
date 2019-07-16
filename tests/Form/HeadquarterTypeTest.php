<?php
// tests/Form/Type/TestedTypeTest.php
namespace App\Tests\Form;

use App\Form\HeadquarterType;
use App\Entity\Headquarter;
use Symfony\Component\Form\Test\TypeTestCase;


class HeadquarterTypeTest extends TypeTestCase
{
    public function testSubmitValidData()
    {
        $formData = [
            'name' => 'testname',
            'city' => 'testcity',
            'country' => 'CO',
        ];

        $hqCompare = new Headquarter();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(HeadquarterType::class, $hqCompare);

        $headquarter = new Headquarter();
        $headquarter->setName("testname");
        $headquarter->setCity("testcity");
        $headquarter->setCountry('CO');

        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($headquarter, $hqCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}
