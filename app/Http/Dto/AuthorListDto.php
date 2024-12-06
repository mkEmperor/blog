<?php

namespace App\Http\Dto;

final class AuthorListDto
{
    /** @var array */
    private array $columns;

    /** @var int */
    private int $offset;

    /** @var int */
    private int $limit;

    /** @var string|null */
    private string|null $sort_by;

    /** @var string|null */
    private string|null $sort_order;

    /** @var array|null */
    private array|null $filter;

    /** @var boolean */
    private bool $need_total;

    public function __construct(array $columns, string $offset = null, string $limit = null, string $sort_by = null, string $sort_order = null, array $filter = null, bool $need_total = null)
    {
        $this->columns = $columns;
        $this->offset = $offset ? intval($offset) : 0;
        $this->limit = $limit ? intval($limit) : 10;
        $this->sort_by = $sort_by ?: $columns[0];
        $this->sort_order = $sort_order === 'desc' ? 'desc' : 'asc';
        $this->filter = $filter ?: [];
        $this->need_total = $need_total && boolval($need_total);
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return string
     */
    public function getSortBy(): string
    {
        return $this->sort_by;
    }

    /**
     * @return string
     */
    public function getSortOrder(): string
    {
        return $this->sort_order;
    }

    /**
     * @return array|null
     */
    public function getFilter(): array|null
    {
        return $this->filter;
    }

    /**
     * @return boolean
     */
    public function getNeedTotal(): bool
    {
        return $this->need_total;
    }
}
