<?php

namespace AppBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class SettingsService
{
    const SHOWROOM_KEY = 'settings_showroom';
    const CAMERA_KEY = 'settings_camera';

    /** @var Session */
    protected $session;

    public function saveShowroom($value)
    {
        $this->session->set(self::SHOWROOM_KEY, $value);
    }

    public function getShowroom()
    {
        return $this->session->get(self::SHOWROOM_KEY, 1);
    }

    public function saveCamera($value)
    {
        $this->session->set(self::CAMERA_KEY, $value);
    }

    public function getCamera()
    {
        return $this->session->get(self::CAMERA_KEY, 1);
    }

    /**
     * @param Session $session
     * @return $this
     */
    public function setSession(Session $session): self
    {
        $this->session = $session;
        return $this;
    }
}
