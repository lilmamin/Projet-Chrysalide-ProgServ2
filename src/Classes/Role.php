<?php

enum Role: string
{
    case Reader = 'reader';
    case Author = 'author';

    public function getLabel(): string
    {
        return match ($this) {
            self::Reader => 'Lecteur·ice',
            self::Author => 'Auteur·ice',
        };
    }
}
