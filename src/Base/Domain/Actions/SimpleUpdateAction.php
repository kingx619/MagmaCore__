<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagmaCore\Base\Domain\Actions;

use MagmaCore\Base\Domain\DomainActionLogicInterface;
use MagmaCore\Base\Domain\DomainTraits;

/**
 * Class which handles the domain logic when adding a new item to the database
 * items are sanitize and validated before persisting to database. The class will
 * also dispatched any validation error before persistence. The logic also implements
 * event dispatching which provide usable data for event listeners to perform other
 * necessary tasks and message flashing
 */
class SimpleUpdateAction implements DomainActionLogicInterface
{

    use DomainTraits;

    /** @var bool */
    protected bool $isRestFul = false;

    /**
     * execute logic for adding new items to the database(). Post data is returned as a collection
     *
     * @param Object $controller - The controller object implementing this object
     * @param string|null $entityObject
     * @param string|null $eventDispatcher - the eventDispatcher for the current object
     * @param string|null $objectSchema
     * @param string $method - the name of the method within the current controller object
     * @param array $rules
     * @param array $additionalContext - additional data which can be passed to the event dispatcher
     * @return NewAction
     */
    public function execute(
        object $controller,
        ?string $entityObject,
        ?string $eventDispatcher,
        ?string $objectSchema,
        string $method,
        array $rules = [],
        array $additionalContext = [],
        mixed $optional = null
    ): self {

        $this->controller = $controller;
        $this->method = $method;
        $this->schema = $objectSchema;
        $formBuilder = $controller->formBuilder;

        if (isset($formBuilder) && $formBuilder?->isFormValid($this->getSubmitValue())) :
            $_optional = ($optional !==null) ? $optional : null;

            $entityCollection = $_optional->getEntity()
                ->wash($formBuilder->getData())
                ->rinse()
                ->dry();
                $data = $entityCollection->all();

            unset($data[$this->getSubmitValue()]); /* remove the submit from the final array */

            $_optional->getRepo()
                    ->getEm()
                    ->getCrud()
                    ->update(
                        array_merge($data, [$_optional->getSchemaID() => $controller->thisRouteID()]), $_optional->getSchemaID()
                    );
                    $this->dispatchSingleActionEvent(
                        $controller,
                        $eventDispatcher,
                        $method,
                        array_merge($data, $additionalContext ? $additionalContext : []),
                        $additionalContext
                    );


        endif;
        return $this;
    }


}


