<?php

namespace Jabe\Model\Knd\ConstructionSupervision\Impl\Instance\Request;

use Jabe\Model\Xml\ModelBuilder;
use Jabe\Model\Xml\Impl\Instance\ModelElementInstanceImpl;
use Jabe\Model\Xml\Impl\Instance\ModelTypeInstanceContext;
use Jabe\Model\Xml\Type\ModelTypeInstanceProviderInterface;
use Jabe\Model\Knd\ConstructionSupervision\Impl\RequestModelConstants;
use Jabe\Model\Knd\ConstructionSupervision\Instance\Request\FIASObjectAddressInterface;

class FIASObjectAddressImpl extends ModelElementInstanceImpl implements FIASObjectAddressInterface
{
    public static function registerType(ModelBuilder $modelBuilder): void
    {
        $typeBuilder = $modelBuilder->defineType(
            FIASObjectAddressInterface::class,
            RequestModelConstants::ELEMENT_NAME_FIAS_OBJECT_ADDRESS
        )
        ->namespaceUri(RequestModelConstants::MODEL_NAMESPACE)
        ->instanceProvider(
            new class implements ModelTypeInstanceProviderInterface
            {
                public function newInstance(ModelTypeInstanceContext $instanceContext): FIASObjectAddressInterface
                {
                    return new FIASObjectAddressImpl($instanceContext);
                }
            }
        );

        $typeBuilder->build();
    }

    public function __construct(ModelTypeInstanceContext $instanceContext)
    {
        parent::__construct($instanceContext);
    }
}
