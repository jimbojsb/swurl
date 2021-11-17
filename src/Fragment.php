<?php
namespace Swurl;

class Fragment extends Parsable
{
    protected function getParsedSeperator(): string
    {
        return '#';
    }

    protected function useAssignmentIfEmpty(): bool
    {
        return false;
    }
}
