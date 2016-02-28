<?php
namespace Util\View\Helper;
use Zend\Mvc\Controller\Plugin\FlashMessenger as ZendFlash;
use Zend\View\Helper\AbstractHelper;

class FlashMessenger extends AbstractHelper
{
    /**
     * @var \Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    protected $flashMessenger;
    /**
     * @var array
     */
    protected $namespaces = array(
        'default',
        'error', 
        'success',
        'info', 
        'warning'
    );

    /**
     * Set the Controller plugin
     * 
     * @param   Zend\Mvc\Controller\Plugin\FlashMessenger
     */
    public function setFlashMessenger($flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;

        return $this;
    }

    /**
     * @return  string
     */
    public function __invoke()
    {
        $messageString = '';            
        foreach($this->namespaces as $ns) {
            $this->flashMessenger->setNamespace($ns);
            $messages = $this->flashMessenger->getMessages();
            if($this->flashMessenger->hasCurrentMessages()) {
                $messages += $this->flashMessenger->getCurrentMessages();
                $this->flashMessenger->clearCurrentMessages();
            }

            if(count($messages) > 0) {
                // Twitter bootstrap message box
                $messageString .= sprintf(
                    '<div class="messagebox alert-%s">
                        <a class="messagebox-closer" href="#close" style="display: inline;" title="Close"></a>
                        <div class="messagebox-text">%s</div>
                    </div>',
                    $ns,
                    implode('<br />', $messages)
                );
            }
        }

        return $messageString;
    }
}