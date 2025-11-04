<?php

class Chapter
{
    private int $id;
    private int $story_id;
    private string $title;
    private string $content;
    private int $position; // ordre des chapitres
    private DateTime $created_at;

    public function __construct(
        int $story_id,
        string $title,
        string $content,
        int $position,
        ?DateTime $created_at = null,
        ?int $id = null
    ) {
        $this->id = $id ?? 0;
        $this->story_id = $story_id;
        $this->title = $title;
        $this->content = $content;
        $this->position = $position;
        $this->created_at = $created_at ?? new DateTime();
    }

    //getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getStoryId(): int
    {
        return $this->story_id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getContent(): string
    {
        return $this->content;
    }
    public function getPosition(): int
    {
        return $this->position;
    }
    public function getCreatedAt(): DateTime
    {
        return $this->created_at;
    }

    public function getInfo(): string
    {
        return "Chapitre {$this->position}: {$this->title} ({$this->created_at->format('Y-m-d')})";
    }
}
