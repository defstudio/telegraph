<?php

namespace DefStudio\Telegraph\Concerns;

use ReflectionClass;

trait CallTraitsMethods
{
    /**
     * @template TValue
     *
     * @param  TValue  $pipeable
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
