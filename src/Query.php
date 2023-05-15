<?php

namespace Swurl;

class Query extends Parsable
{
    protected function getParsedSeperator(): string
    {
        return '?';
    }

    protected function useAssignmentIfEmpty(): bool
    {
        return true;
    }
}
