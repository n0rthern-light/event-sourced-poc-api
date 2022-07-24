<?php

namespace App\Shared\Infrastructure\SQL;

final class SelectSqlBuilder
{
    private array $whatFields;
    private string $fromTable;
    private ?string $fromAlias = null;
    private array $andConditions = [];
    private array $orConditions = [];
    private array $orderBy;
    private bool $single = false;

    public function what(array $fields): static
    {
        $this->whatFields = $fields;

        return $this;
    }

    public function from(string $table, ?string $alias = null): static
    {
        $this->fromTable = $table;
        $this->fromAlias = $alias;

        return $this;
    }

    public function where(string $condition): static
    {
        $this->andConditions[] = $condition;

        return $this;
    }

    public function orWhere(string $condition): static
    {
        $this->orConditions[] = $condition;

        return $this;
    }

    public function orderBy(string $column, string $direction): static
    {
        $this->orderBy = [];
        $this->orderBy[$column] = $direction;

        return $this;
    }

    public function limitToOne(): static
    {
        $this->single = true;

        return $this;
    }

    public function buildSQL(): string
    {
        $sql = 'select ';

        $sql .= \implode(',', $this->whatFields) . ' ';
        $sql .= 'from ' . $this->fromTable . ' ' . ($this->fromAlias ? ($this->fromAlias . ' ') : '');

        foreach($this->andConditions as $condition) {
            if (!\str_contains($sql, 'where')) {
                $sql .= 'where ';
            } else {
                $sql .= 'and ';
            }

            $sql .= $condition . ' ';
        }

        foreach($this->orConditions as $condition) {
            if (!\str_contains($sql, 'where')) {
                $sql .= 'where ';
            } else {
                $sql .= 'or ';
            }

            $sql .= $condition . ' ';
        }

        if (isset($this->orderBy)) {
            $key = \array_key_first($this->orderBy);
            $sql .= 'order by ' . $key . ' ' . $this->orderBy[$key] . ' ';
        }

        if ($this->single) {
            $sql .= 'limit 1 ';
        }

        return \trim($sql) . ';';
    }
}
