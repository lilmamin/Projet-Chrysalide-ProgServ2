<?php

require_once __DIR__ . '/Chapter.php';

class Story
{
    private int $id;
    private int $author_id;
    private string $title;
    private string $summary;
    private bool $is_completed;
    private ?DateTime $published_at;
    private ?DateTime $updated_at;
    private array $chapters = [];



    public function __construct(
        int $author_id,
        string $title,
        string $summary,
        bool $is_completed = false,
        ?DateTime $published_at = null,
        ?DateTime $updated_at = null,
        ?int $id = null,
        array $chapters = []
    ) {
        $this->id = $id ?? 0;
        $this->author_id = $author_id;
        $this->title = $title;
        $this->summary = $summary;
        $this->is_completed = $is_completed;
        $this->published_at = $published_at;
        $this->updated_at = $updated_at;
        $this->chapters = $chapters;
    }

    // getters
    public function getId(): int
    {
        return $this->id;
    }
    public function getUserId(): int
    {
        return $this->author_id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }
    public function getSummary(): string
    {
        return $this->summary;
    }
    public function isCompleted(): bool
    {
        return $this->is_completed;
    }
    public function getPublishedAt(): ?DateTime
    {
        return $this->published_at;
    }
    public function getChapterCount(): int
    {
        return count($this->chapters);
    }


    public function getUpdatedAt(): ?DateTime
    {
        return $this->updated_at;
    }

    public function getChapters(): array
    {
        return $this->chapters;
    }

    // setters
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }
    public function setSummary(string $summary): void
    {
        $this->summary = $summary;
    }
    public function setCompleted(bool $completed): void
    {
        $this->is_completed = $completed;
    }
    public function setChapters(array $chapters): void
    {
        $this->chapters = $chapters;
    }

    // actions
    public function addChapter(Chapter $chapter): void
    {
        $this->chapters[] = $chapter;
    }

    public function publish(): void
    {
        $this->published_at = new DateTime();
    }

    public function update(): void
    {
        $this->updated_at = new DateTime();
    }

    public function getInfo(): string
    {
        $status = $this->is_completed ? "Terminée" : "En cours";
        $published = $this->published_at ? $this->published_at->format('Y-m-d H:i') : "Non publiée";
        $chapterCount = count($this->chapters);
        return "{$this->title} ({$status}) – {$chapterCount} chapitres – Publiée le : {$published}";
    }
}
