<?php


namespace App\Core;

class ResourceManager
{
    private static array $handles = [];

    /**
     * @param string $handle
     * @return Resource | null
     */
    public static function getResource(string $handle) : ?Resource {
        if (self::existResource($handle)) {
            return self::$handles[$handle];
        }
        return null;
    }

    /**
     * @return array<Resource>
     */
    public static function getResources() : array {
        return self::$handles;
    }

    /**
     * @return void
     */
    private static function sortResources() : void {
        function cmp($a, $b): int
        {
            if ($a->getOrder() == $b->getOrder()) {
                return 0;
            }
            return ($a->getOrder() < $b->getOrder()) ? -1 : 1;
        }

        usort(self::$handles, "cmp");
    }

    /**
     * @param string $handle
     * @param string $src
     * @param string $type
     * @param int $order
     * @param array $deps
     * @return Resource|null
     */
    public static function addResource(string $handle, string $src, string $type, int $order, array $deps) : ?Resource
    {
        $resource = new Resource($handle, $src, $type, $order, $deps);

        if ($resource->isActive()) {
            self::$handles[$handle] = $resource;
            self::sortResources();

            return $resource;
        }

        return null;
    }

    /**
     * @param string $handle
     * @return bool
     */
    private static function existResource(string $handle) : bool {
        return array_key_exists($handle, self::$handles);
    }

    public static function renderResources() : string {
        $html = "";

        foreach (self::$handles as $key => $resource) {
            $deps = $resource->getDeps();
            $countDeps = count($deps);
            $issetDeps = 0;

            foreach ($deps as $k => $dep) {
                if (self::existResource($dep)) $issetDeps++;
            }

            if ($issetDeps === $countDeps) {
                if ($resource->getType() === Resource::RESOURCE_TYPE_STYLE) {
                    $html .= "<link rel='stylesheet' id='" . $resource->getHandle() . "-style' href='" . $resource->getSrc() . "'>\n";
                }
                if ($resource->getType() === Resource::RESOURCE_TYPE_SCRIPT) {
                    $html .= "<script id='" . $resource->getHandle() . "-script' src='" . $resource->getSrc() . "'></script>\n";
                }
            }
        }

        return $html;
    }
}
