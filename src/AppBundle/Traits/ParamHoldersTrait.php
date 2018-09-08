<?php

namespace AppBundle\Traits;

trait ParamHoldersTrait
{
    /** @var string */
    protected $apiHostname;

    /**
     * @param string $apiHostname
     * @return $this
     */
    public function setApiHostname(string $apiHostname): self
    {
        $this->apiHostname = $apiHostname;

        return $this;
    }
}
