<?php
namespace Util\View\Helper;

use Zend\ServiceManager\ServiceLocatorInterface;

use Zend\View\Helper\AbstractHelper;

class BaseFullPath  extends AbstractHelper
{    
    public function __construct(ServiceLocatorInterface $sm)
    {
        $uri = $sm->getServiceLocator()->get('application')
                ->getMvcEvent()->getRequest()->getUri();
        
        $baseFullPath = $currentHost = $uri->getScheme() 
                . '://' . $uri->getHost() 
                . $uri->getPath();
        
        $this->setBasePath($baseFullPath);
    }
    
    /**
     * Base path
     *
     * @var string
     */
    protected $basePath;

    /**
     * Returns site's base path, or file with base path prepended.
     *
     * $file is appended to the base path for simplicity.
     *
     * @param  string|null $file
     * @throws Exception\RuntimeException
     * @return string
     */
    public function __invoke($file = null)
    {
        if (null === $this->basePath) {
            throw new Exception\RuntimeException('No base path provided');
        }

        if (null !== $file) {
            $file = '/' . ltrim($file, '/');
        }

        return $this->basePath . $file;
    }

    /**
     * Set the base path.
     *
     * @param  string $basePath
     * @return self
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '/');
        return $this;
    }
}
