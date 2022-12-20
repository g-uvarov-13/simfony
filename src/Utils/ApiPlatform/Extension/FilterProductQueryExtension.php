<?php

namespace App\Utils\ApiPlatform\Extension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface as LegacyQueryNameGeneratorInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use App\Entity\Product;
use Doctrine\ORM\QueryBuilder;

class FilterProductQueryExtension implements QueryCollectionExtensionInterface,QueryItemExtensionInterface
{

    public function applyToCollection(QueryBuilder $queryBuilder, LegacyQueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
       $this->andWhere($queryBuilder,$resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, Operation $operation = null, array $context = []): void
    {
        $this->andWhere($queryBuilder,$resourceClass);
    }

    private function andWhere(QueryBuilder $queryBuilder, string $resourceClass){
        if(Product::class !== $resourceClass ){
            return;
        }

        $rootAlias =  $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(
          sprintf("%s.isDeleted = '0'",$rootAlias)
        );
    }
}