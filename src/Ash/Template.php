<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare (strict_types = 1);

namespace MagmaCore\Ash;

use JetBrains\PhpStorm\Pure;

class Template extends AbstractTemplate
{

    /** @var TemplateEnvironment */
    protected TemplateEnvironment $templateEnvironment;

    /**
     * Main class constructor
     *
     * @param TemplateEnvironment $templateEnvironment
     */
    #[Pure] public function __construct(TemplateEnvironment $templateEnvironment)
    {
        $this->templateEnvironment = $templateEnvironment;
        parent::__construct($templateEnvironment);
    }

    /**
     * Display the template
     *
     * @param string $file
     * @param array $context
     */
    public function view(string $file, array $context = [])
    {
        $fileCache = $this->cache(TEMPLATES . $file);
        extract($context, EXTR_SKIP);
        require $fileCache;
    }

    /**
     * Display the template from the error resource directory
     *
     * @param string $file
     * @param array $context
     */
    public function errorView(string $file, array $context = [])
    {
        $fileCache = $this->cache(ERROR_RESOURCE . $file);
        extract($context, EXTR_SKIP);
        require $fileCache;
    }


}
