<?php

namespace Smartbox\ApiBundle\Tests\Entity;


use JMS\Serializer\Serializer;
use Smartbox\CoreBundle\Entity\BasicTypes\EntityArray;
use Smartbox\CoreBundle\Entity\BasicTypes\Integer;
use Smartbox\CoreBundle\Entity\BasicTypes\String;
use Smartbox\CoreBundle\Entity\Entity;
use Smartbox\CoreBundle\Entity\EntityInterface;
use Smartbox\CoreBundle\Tests\BaseTestCase;

class EntityTest extends BaseTestCase
{

    public function validAndInvalidStringsDataProvider()
    {
        $exceptClass = 'InvalidArgumentException';

        return array(
            array('xxx', null),
            array(null, null),
            array("", null),
            array(-1, $exceptClass),
            array(234, $exceptClass),
            array(12.32, $exceptClass)
        );
    }

    /**
     * @dataProvider validAndInvalidStringsDataProvider
     * @param $value
     * @param $exceptionClass
     */
    public function testSetGetGroup($value, $exceptionClass)
    {
        $entity = new Entity();
        $mustFail = !empty($exceptionClass);

        try {
            $entity->setGroup($value);

            if ($mustFail) {
                $this->fail("Expected exception $exceptionClass was not raised");
            } else {
                $this->assertEquals($value, $entity->getGroup());
            }
        } catch (\Exception $ex) {
            if ($mustFail) {
                $this->assertEquals(get_class($ex), $exceptionClass);
            } else {
                $this->fail("Not expected exception with message: ".$ex->getMessage());
            }
        }

    }

    /**
     * @dataProvider validAndInvalidStringsDataProvider
     * @param $value
     * @param $exceptionClass
     */
    public function testSetGetVersion($value, $exceptionClass)
    {
        $entity = new Entity();
        $mustFail = !empty($exceptionClass);

        try {
            $entity->setVersion($value);

            if ($mustFail) {
                $this->fail("Expected exception $exceptionClass was not raised");
            } else {
                $this->assertEquals($value, $entity->getVersion());
            }
        } catch (\Exception $ex) {
            if ($mustFail) {
                $this->assertEquals(get_class($ex), $exceptionClass);

                return;
            } else {
                $this->fail("Not expected exception with message: ".$ex->getMessage());
            }
        }
    }

    public function entitiesToSerializeProvider()
    {
        $arrEntityA = new EntityArray();
        $arrEntityA->set('AAAA', new String("XXXXXXX"));

        $arrEntity = new EntityArray();
        $arrEntity->set('response', $arrEntityA);
        $arrEntity->set('response2', $arrEntityA);
        $arrEntity->set('number', new Integer(2));
        $arrEntity->set('string', new String("Lorem ipsum"));
        $arrEntity->set('other', $arrEntityA);

        return array(
            array($arrEntity)
        );
    }

    /**
     * @dataProvider entitiesToSerializeProvider
     */
    public function testSerializationEntity(EntityInterface $entity)
    {

        /** @var Serializer $serializer */
        $serializer = $this->getContainer()->get('serializer');

        $json = $serializer->serialize($entity, 'json');
        $entityAfterJson = $serializer->deserialize($json, Entity::class, 'json');
        $this->assertEquals($entity, $entityAfterJson);


        $xml = $serializer->serialize($entity, 'xml');
        $entityAfterXml = $serializer->deserialize($xml, Entity::class, 'xml');
        $this->assertEquals($entity, $entityAfterXml);
    }

}