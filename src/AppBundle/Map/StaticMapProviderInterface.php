<?php

namespace AppBundle\Map;

use AppBundle\Geocoder\Coordinates;

interface StaticMapProviderInterface
{
    /**
     * Generate an image for the given coordinates using a static map provider.
     *
     * @param Coordinates $coordinates the coordinates to generate a map for
     *
     * @return string|false return the map image content or false if an error occured
     */
    public function get(Coordinates $coordinates);
}
