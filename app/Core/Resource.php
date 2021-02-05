<?php

namespace App\Core;

use Illuminate\Support\Facades\Http;

class Resource
{
    const RESOURCE_TYPE_STYLE = 'style';
    const RESOURCE_TYPE_SCRIPT = 'script';

    private string $handle;
    private string $src;
    private string $type;
    private int $order;
    private array $deps;

    /**
     * @param string $handle
     * @param string $src
     * @param string $type
     * @param int $order
     * @param array $deps
     */
    public function __construct(string $handle, string $src, string $type, int $order, array $deps)
    {
        $this->handle = $handle;
        $this->src = $src;
        $this->type = $type;
        $this->order = $order;
        $this->deps = $deps;
    }

    /**
     * @return bool
     */
    private function isResourceExists() : bool {
        if ($this->isResourceUrl()) {
            return Http::get($this->src)->ok();
        }

        if (file_exists($this->src)) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isActive() : bool {
        return $this->isResourceExists();
    }

    /**
     * @return bool
     */
    private function isResourceUrl() : bool {
        return filter_var($this->src, FILTER_VALIDATE_URL);
    }

    /**
     * @return array
     */
    public function getDeps(): array
    {
        return $this->deps;
    }

    /**
     * @return string
     */
    public function getHandle(): string
    {
        return $this->handle;
    }

    /**
     * @return int
     */
    public function getOrder(): int
    {
        return $this->order;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
