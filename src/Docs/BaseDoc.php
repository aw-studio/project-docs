<?php

namespace Docs\Docs;

use Docs\Contracts\Doc;
use Docs\Contracts\Engine;
use Docs\Contracts\Markdownable;
use Docs\Markdown\Title;
use Docs\Support\Markdown;

abstract class BaseDoc implements Doc, Markdownable
{
    /**
     * Engine instance.
     *
     * @var Engine
     */
    protected $engine;

    /**
     * Doc depth.
     *
     * @var int
     */
    protected $depth = 1;

    /**
     * Get Doc title.
     *
     * @return string
     */
    abstract public function title();

    /**
     * Describe block.
     *
     * @return array
     */
    abstract public function describe();

    /**
     * Create new BaseDoc instance.
     *
     * @param  Engine $engine
     * @param  string $path
     * @return void
     */
    public function __construct(Engine $engine, $path)
    {
        $this->engine = $engine;
        $this->path = $path;
    }

    /**
     * Get doc file path.
     *
     * @return void
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Create subTitle.
     *
     * @param  string $title
     * @param  int    $depth
     * @return Title
     */
    public function subTitle($title, $depth = 1)
    {
        return Markdown::title($title, $this->depth + $depth);
    }

    /**
     * Get title.
     *
     * @return Title
     */
    public function getTitle()
    {
        $title = $this->title();

        if ($title instanceof Title) {
            return $title;
        }

        return Markdown::title($title, $this->depth)->toMarkdown();
    }

    /**
     * Get description.
     *
     * @return Collection
     */
    public function getDescription()
    {
        $description = $this->describe();

        if (is_array($description)) {
            $description = collect($description);
        }

        return $description->flatten();
    }

    /**
     * Set depth.
     *
     * @param  int   $depth
     * @return $this
     */
    public function setDepth(int $depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Get depth.
     *
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * Parse Doc to markdown.
     *
     * @return string
     */
    public function toMarkdown()
    {
        return $this->engine->getMarkdown($this);
    }

    /**
     * Parse Doc to Html.
     *
     * @return string
     */
    public function toHtml()
    {
        return $this->engine->getHtml($this);
    }

    /**
     * Parse Doc to string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toHtml() ?? '';
    }
}
