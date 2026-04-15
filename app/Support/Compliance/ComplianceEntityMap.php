<?php

namespace App\Support\Compliance;

use App\Models\Driver;
use App\Models\Supplier;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class ComplianceEntityMap
{
    public const MAP = [
        'vehicle' => Vehicle::class,
        'driver' => Driver::class,
        'supplier' => Supplier::class,
    ];

    public static function morphMap(): array
    {
        return self::MAP;
    }

    public static function aliasFor(Model|string $entity): string
    {
        $class = $entity instanceof Model ? $entity::class : self::modelClassFor($entity);
        $alias = array_search($class, self::MAP, true);

        if (! is_string($alias)) {
            throw new InvalidArgumentException("Unsupported compliance entity [{$class}].");
        }

        return $alias;
    }

    public static function modelClassFor(string $entityType): string
    {
        if (isset(self::MAP[$entityType])) {
            return self::MAP[$entityType];
        }

        if (in_array($entityType, self::MAP, true)) {
            return $entityType;
        }

        throw new InvalidArgumentException("Unsupported compliance entity type [{$entityType}].");
    }
}
