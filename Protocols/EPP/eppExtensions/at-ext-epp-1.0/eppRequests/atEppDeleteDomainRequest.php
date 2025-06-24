<?php
namespace Metaregistrar\EPP;

class atEppDeleteDomainRequest extends eppDeleteDomainRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;
    public const SCHEDULE_DELETE_NOW = "now";
    public const SCHEDULE_DELETE_EXPIRATION = "expiration";

    public function __construct(eppDomain $deleteinfo, $namespacesinroot = true, $scheduledate = self::SCHEDULE_DELETE_NOW, atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($deleteinfo, $namespacesinroot);
        $this->addATScheduledateExtension($scheduledate);
        $this->setAtExtensions();
        $this->addSessionId();
    }

    public function addATScheduledateExtension($scheduleType) {
        $deleteext = $this->createElement('at-ext-domain:delete');
        $deleteext->setAttribute('xmlns:at-ext-domain', 'http://www.nic.at/xsd/at-ext-domain-1.0');
        $scheduleext = $this->createElement('at-ext-domain:scheduledate', $scheduleType);
        $deleteext->appendChild($scheduleext);
        $this->getExtension()->appendChild($deleteext);
    }
}