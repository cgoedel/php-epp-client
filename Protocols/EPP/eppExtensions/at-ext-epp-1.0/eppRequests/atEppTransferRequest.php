<?php
namespace Metaregistrar\EPP;

use DOMException;

class atEppTransferRequest extends eppTransferRequest
{
    use atEppCommandTrait;

    /**
     * @var atEppExtensionChain|null
     */
    protected $atEppExtensionChain = null;

    public function __construct($operation, $object, ?atEppExtensionChain $atEppExtensionChain = null, bool $usecdata = false)
    {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($operation, $object, $usecdata);
        $this->setAtExtensions();
        $this->addSessionId();
    }

    /**
     * @throws DOMException
     */
    public function setDomainRequest(eppDomain $domain)
    {
        #
        # Object create structure
        #
        $transfer = $this->createElement('transfer');
        $transfer->setAttribute('op', self::OPERATION_REQUEST);

        $domainobject = $this->createElement('domain:transfer');
        $domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        if ($domain->getPeriod()) {
            $domainperiod = $this->createElement('domain:period', $domain->getPeriod());
            $domainperiod->setAttribute('unit', eppDomain::DOMAIN_PERIOD_UNIT_Y);
            $domainobject->appendChild($domainperiod);
        }
        if (strlen($domain->getAuthorisationCode())) {
            $authinfo = $this->createElement('domain:authInfo');

            if ($this->useCdata()) {
                $pw = $authinfo->appendChild($this->createElement('domain:pw'));
                $pw->appendChild($this->createCDATASection($domain->getAuthorisationCode()));
            } else {
                $authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
            }

            $domainobject->appendChild($authinfo);
        }
        $transfer->appendChild($domainobject);
        $this->getCommand()->appendChild($transfer);
    }

}