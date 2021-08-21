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

namespace MagmaCore\Ash\Components\Uikit;

class UikitPaginationExtension
{

    /** @var string */
    public const NAME = 'uikit_pagination';

    /**
     * Register the UIkit default pagination html wrapper
     *
     * @param object|null $controller
     * @return string
     */
    public function register(object $controller = null): string
    {
        return '
        <section class="">
            <nav aria-label="Pagination" uk-navbar>
                <div class="uk-navbar-left" style="margin-top: -15px;">
                </div>
                <div class="uk-navbar-right">
                <small class="uk-margin-large-right">Rows per page 10 <span class="uk-margin-top uk-margin-left"><ion-icon name="caret-down-outline"></ion-icon></span></small>
                <small>' . $this->infoPaging($controller) . '</small>
                    <ul class="uk-pagination">
                    ' . $controller->tableGrid->previousPaging($this->status($controller), $this->statusQueried($controller)) . $controller->tableGrid->nextPaging($this->status($controller), $this->statusQueried($controller)) . '
                    </ul>
                </div>
            </nav>
        </section>
        ' . PHP_EOL;
    }

    /**
     * Get the status from the current queried if any
     *
     * @param object $controller
     * @return string
     */
    private function status(object $controller): string
    {
        return $controller->tableGrid->getStatus();
    }

    /**
     * Return queried status value
     *
     * @param object $controller
     * @return mixed
     */
    private function statusQueried(object $controller): mixed
    {
        return $controller->tableGrid->getQueriedStatus();
    }

    /**
     * Return information regarding the pagination current count, current page
     * etc..
     *
     * @param object $controller
     * @return string
     */
    private function infoPaging(object $controller): string
    {
        return sprintf('%s - %s of %s', $controller->tableGrid->getCurrentPage(), $controller->tableGrid->getTotalPages(), $controller->tableGrid->getTotalRecords());
    }
}
