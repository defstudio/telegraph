<?php

namespace DefStudio\Telegraph\Concerns;

use ReflectionClass;

trait CallTraitsMethods
{
    /**
     * @template TValue
     * @param string $methodName
     * @param TValue $pipeable
     * @param mixed ...$params
     *
     * @return TValue
     */
    protected function pipeTraits(string $methodName, mixed $pipeable, mixed ...$params): mixed
    {
        $reflection = new ReflectionClass(self::class);
        foreach ($reflection->getTraitNames() as $trait) {
            $trait = class_basename($trait);

            $traitMethod = "$methodName$trait";

            if (method_exists($this, $traitMethod)) {
                $pipeable = $this->$traitMethod($pipeable, ...$params);
            }
        }

        return $pipeable;
    }
}
