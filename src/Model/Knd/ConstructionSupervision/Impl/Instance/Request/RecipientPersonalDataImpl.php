<?php

namespace Jabe\Model\Knd\ConstructionSupervision\Impl\Instance\Request;

use Jabe\Model\Xml\ModelBuilder;
use Jabe\Model\Xml\Impl\Instance\ModelElementInstanceImpl;
use Jabe\Model\Xml\Impl\Instance\ModelTypeInstanceContext;
use Jabe\Model\Xml\Type\ModelTypeInstanceProviderInterface;
use Jabe\Model\Knd\ConstructionSupervision\Impl\RequestModelConstants;
use Jabe\Model\Knd\ConstructionSupervision\Instance\Request\{
    CitizenshipInterface,
    DateBirthInterface,
    DocumentPersonalInterface,
    EmailInterface,
    FactAddressInterface,
    FirstnameInterface,
    FullfioInterface,
    GenderInterface,
    LastnameInterface,
    MiddlenameInterface,
    PhoneInterface,
    RecipientPersonalDataInterface,
    RegAddressInterface,
    SnilsInterface
};

class RecipientPersonalDataImpl extends ModelElementInstanceImpl implements RecipientPersonalDataInterface
{
    private $citizenship;
    private $dateBirth;
    private $documentPersonal;
    private $email;
    private $factAddress;
    private $firstName;
    private $fullfio;
    private $gender;
    private $lastname;
    private $middlename;
    private $phone;
    private $regAddress;
    private $snils;

    public static function registerType(ModelBuilder $modelBuilder): void
    {
        $typeBuilder = $modelBuilder->defineType(
            RecipientPersonalDataInterface::class,
            RequestModelConstants::ELEMENT_NAME_RECIPIENT_PERSONAL_DATA
        )
        ->namespaceUri(RequestModelConstants::MODEL_NAMESPACE)
        ->instanceProvider(
            new class implements ModelTypeInstanceProviderInterface
            {
                public function newInstance(ModelTypeInstanceContext $instanceContext): RecipientPersonalDataInterface
                {
                    return new RecipientPersonalDataImpl($instanceContext);
                }
            }
        );

        $sequenceBuilder = $typeBuilder->sequence();

        self::$citizenship = $sequenceBuilder->element(CitizenshipInterface::class)
        ->build();
        self::$dateBirth = $sequenceBuilder->element(DateBirthInterface::class)
        ->build();
        self::$documentPersonal = $sequenceBuilder->element(DocumentPersonalInterface::class)
        ->build();
        self::$email = $sequenceBuilder->element(EmailInterface::class)
        ->build();
        self::$factAddress = $sequenceBuilder->element(FactAddressInterface::class)
        ->build();
        self::$firstName = $sequenceBuilder->element(FirstnameInterface::class)
        ->build();
        self::$fullfio = $sequenceBuilder->element(FullfioInterface::class)
        ->build();
        self::$gender = $sequenceBuilder->element(GenderInterface::class)
        ->build();
        self::$lastname = $sequenceBuilder->element(LastnameInterface::class)
        ->build();
        self::$middlename = $sequenceBuilder->element(MiddlenameInterface::class)
        ->build();
        self::$phone = $sequenceBuilder->element(PhoneInterface::class)
        ->build();
        self::$regAddress = $sequenceBuilder->element(RegAddressInterface::class)
        ->build();
        self::$snils = $sequenceBuilder->element(SnilsInterface::class)
        ->build();

        $typeBuilder->build();
    }

    public function __construct(ModelTypeInstanceContext $instanceContext)
    {
        parent::__construct($instanceContext);
    }

    public function getCitizenship(): CitizenshipInterface
    {
        return self::$citizenship->getChild($this);
    }

    public function getDateBirth(): DateBirthInterface
    {
        return self::$dateBirth->getChild($this);
    }

    public function getDocumentPersonal(): DocumentPersonalInterface
    {
        return self::$documentPersonal->getChild($this);
    }

    public function getEmail(): EmailInterface
    {
        return self::$email->getChild($this);
    }

    public function getFactAddress(): FactAddressInterface
    {
        return self::$factAddress->getChild($this);
    }

    public function getFirstname(): FirstnameInterface
    {
        return self::$firstname->getChild($this);
    }

    public function getFullfio(): FullfioInterface
    {
        return self::$fullfio->getChild($this);
    }

    public function getGender(): GenderInterface
    {
        return self::$gender->getChild($this);
    }

    public function getLastname(): LastnameInterface
    {
        return self::$lastname->getChild($this);
    }

    public function getMiddlename(): MiddlenameInterface
    {
        return self::$middlename->getChild($this);
    }

    public function getPhone(): PhoneInterface
    {
        return self::$phone->getChild($this);
    }

    public function getRegAddress(): RegAddressInterface
    {
        return self::$regAddress->getChild($this);
    }

    public function getSnils(): SnilsInterface
    {
        return self::$snils->getChild($this);
    }
}
