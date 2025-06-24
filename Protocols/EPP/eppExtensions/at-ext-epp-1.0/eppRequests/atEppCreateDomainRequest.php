<?php
namespace Metaregistrar\EPP;

class atEppCreateDomainRequest extends eppCreateDomainRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($createinfo,atEppExtensionChain $atEppExtensionChain=null, $forcehostattr = true) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($createinfo,$forcehostattr);
    }

    public function setDomain(eppDomain $domain) {
        parent::setDomain($domain);

        // The Nic.at API seems to require the presence of the domain:pw element, even if empty.
        // If the element is missing, an error is returned.
        if (!is_string($domain->getAuthorisationCode()) || strlen($domain->getAuthorisationCode()) <= 0) {
            $authinfo = $this->createElement('domain:authInfo');
            $authinfo->appendChild($this->createElement('domain:pw', ''));
            $this->domainobject->appendChild($authinfo);
        }

        $this->setAtExtensions();
    }
}