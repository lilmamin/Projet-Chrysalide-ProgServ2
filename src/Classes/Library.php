<?php

class Library
{
    private int $id;
    private int $user_id;
    private int $story_id;
    private DateTime $added_at;

    public function __construct(
        int $user_id,
        int $story_id,
        ?DateTime $added_at = null,
        ?int $id = null
    ) {
        $this->id = $id ?? 0;
        $this->user_id = $user_id;
        $this->story_id = $story_id;
        $this->added_at = $added_at ?? new DateTime();
    }

    // Getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function getStoryId(): int
    {
        return $this->story_id;
    }

    public function getAddedAt(): DateTime
    {
        return $this->added_at;
    }

    // Actions / helpers
    public function getInfo(): string
    {
        return "Story #{$this->story_id} ajoutée à la bibliothèque de l’utilisateur #{$this->user_id} le {$this->added_at->format('Y-m-d H:i')}";
    }
}
