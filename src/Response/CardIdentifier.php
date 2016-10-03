<?php namespace Academe\SagePay\Psr7\Response;

/**
 * Value object to hold the card identifier, returned by SagePay.
 * Reasonable validation is done at creation.
 */

use DateTime;
use Exception;
use Psr\Http\Message\ResponseInterface;
use Academe\SagePay\Psr7\Helper;

class CardIdentifier extends AbstractResponse
{
    protected $cardIdentifier;
    protected $expiry;
    protected $cardType;

    /**
     * @param array|object $data The parsed data returned by Sage Pay.
     * @return $this
     */
    protected function setData($data)
    {
        $this->cardIdentifier = Helper::dataGet($data, 'cardIdentifier', Helper::dataGet($data, 'card-identifier', null));

        if ($expiry = Helper::dataGet($data, 'expiry', null)) {
            $this->expiry = Helper::parseDateTime($expiry);
        }

        $this->cardType = Helper::dataGet($data, 'cardType', null);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCardIdentifier()
    {
        return $this->cardIdentifier;
    }

    /**
     * The expiry timestamp of the card identifier resource, not the expiry date of the card.
     * @return mixed
     */
    public function getExpiry()
    {
        return $this->expiry;
    }

    /**
     * @return mixed
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        // Use the default system timezone; the DateTime comparison
        // operation will handle any timezone conversions.
        // Note that this does not do a remote check with the Sage Pay
        // API. We can only find out if it is really still valid by
        // attempting to use it.
        // Note that the dropin form does not provide and expiry time,
        // so will always appear to be expired.

        $time_now = new DateTime();

        return ! isset($this->expiry) || $time_now > $this->expiry;
    }

    /**
     * Reduce the object to an array so it can be serialised and stored between pages.
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'cardIdentifier' => $this->getCardIdentifier(),
            'expiry' => $this->getExpiry() ? $this->getExpiry()->format(Helper::SAGEPAY_DATE_FORMAT) : null,
            'cardType' => $this->getCardType(),
            'httpCode' => $this->getHttpCode(),
        ];
    }
}
