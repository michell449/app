<?php

namespace CfdiUtils\Internals;

/**
 * Build a command array from a template
 *
 * NOTE: Changes will not be considering a breaking compatibility change since this utility is for internal usage only
 * @internal
 */
class CommandTemplate
{
    public function create(string $template, array $arguments): array
    {
        $command = [];
        $parts = array_filter(explode(' ', $template), function (string $part): bool {
            return ('' !== $part);
        });

        $argumentPosition = 0;
        foreach ($parts as $value) {
            if ('?' === $value) { // argument insert
                $value = $arguments[$argumentPosition] ?? '';
                $argumentPosition = $argumentPosition + 1;
            }
            $command[] = $value;
        }

        return $command;
    }
}
