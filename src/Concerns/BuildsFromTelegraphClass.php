<?php

namespace DefStudio\Telegraph\Concerns;

use DefStudio\Telegraph\Support\Testing\Fakes\TelegraphFake;
use DefStudio\Telegraph\Telegraph;
use ReflectionClass;

trait BuildsFromTelegraphClass
{
    public static function makeFrom(Telegraph $telegraph): self
    {
        $newInstance = app(self::class);

        $reflection = new ReflectionClass($telegraph);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->name;
            $property->setAccessible(true);

            if ($property->isInitialized($telegraph)) {
                if ($property->isStatic()) {
                    continue;
                }
                $newInstance->$propertyName = $property->getValue($telegraph);
            }
        }

        return $newInstance;
    }
}
