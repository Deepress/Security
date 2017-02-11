<?php

namespace Arachne\Security\Authentication;

use Nette\Security\IIdentity;

/**
 * @author Jáchym Toušek <enumag@gmail.com>
 */
interface FirewallInterface
{
    const LOGOUT_MANUAL = 1;
    const LOGOUT_INACTIVITY = 2;
    const LOGOUT_INVALID_IDENTITY = 3;
    const LOGOUT_BROWSER_CLOSED = 4;

    /**
     * @param IIdentity $identity
     */
    public function login(IIdentity $identity);

    public function logout();

    /**
     * @return IIdentity
     */
    public function getIdentity();

    /**
     * @return IIdentity
     */
    public function getExpiredIdentity();

    /**
     * @return int
     */
    public function getLogoutReason();

    /**
     * @param string|int|\DateTime $time
     * @param bool                 $browserClosed
     */
    public function setExpiration($time, $browserClosed = true);
}
