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

use MagmaCore\Utility\Stringify;
use MagmaCore\UserManager\UserColumn;

class UikitSimplePaginationExtension
{

    /** @var string */
    public const NAME = 'uikit_simple_pagination';

    /**
     * Register the UIkit default pagination html wrapper
     *
     * @param object|null $controller
     * @return string
     */
    public function register(object $controller = null): string
    {
        $name = $controller->thisRouteController();
        $name = Stringify::pluralize($name);
        $name = Stringify::capitalize($name);
        $html = '<section>';
            $html .= '<nav aria-label="Pagination" uk-navbar>';
                $html .= '<div class="uk-navbar-left">';
                $html .= $this->navContentLeft($controller, $name);
                $html .= '</div>';
                $html .= '<div class="uk-navbar-center">';
                $html .= $this->navContentCentre($controller, $name);
                $html .= '</div>';
                $html .= '<div class="uk-navbar-right">';
                $html .= $this->navContentRight($controller);
                $html .= '</div>';
            $html .= '</nav>';
        $html .= '</section>';

        return $html;

    //     <li>
    //     <a href="#" uk-tooltip="Filter ' . $name . '"><span class="ion-28"><ion-icon name="filter-outline"></ion-icon></span></a>
    //     ' . $this->getSearchableColumns($controller) . '
    //  </li>

    }

    private function navContentLeft($controller, $name)
    {
        return '
        <ul class="uk-iconnav">
        <li><button uk-tooltip="Select All" type="button" class="uk-button uk-button-small uk-button-default">
        <input type="checkbox" class="uk-checkbox" name="selectAllDomainList" id="selectAllDomainList" />
        <span></span>
        </button></li>
        <li><a data-turbo="true" href="/admin/' . $controller->thisRouteController() . '/new" uk-tooltip="Add New ' . $name . '"><span class="ion-21"><ion-icon name="add-outline"></ion-icon></span></a></li>
        
        <li class=""><button type="submit" class="uk-button uk-button-small uk-button-text" name="bulk-delete" id="bulk_delete" uk-tooltip="Bulk Delete"><span class="ion-21"><ion-icon name="trash-outline"></ion-icon></span></button></li>

        <li class=""><button type="submit" class="uk-button uk-button-small uk-button-text" name="bulk-clone" id="bulk_clone" uk-tooltip="Bulk Copy"><span class="ion-21"><ion-icon name="copy-outline"></ion-icon></span></button>
        </li>

        <li><a uk-tooltip="Total ' . $name . '" class="uk-link-reset uk-text-meta" href="#"> (' . (isset($controller->repository) ? $controller->repository->getRepo()->count() : 0) . ')</a></li>
        </ul>

        ';
    }

    private function navContentRight(object $controller)
    {
        return '
        <small>' . $this->infoPaging($controller) . '</small>
        <ul class="uk-pagination">
        ' . $controller->tableGrid->previousPaging($this->status($controller), $this->statusQueried($controller)) . $controller->tableGrid->nextPaging($this->status($controller), $this->statusQueried($controller)) . '
        </ul>
        ';
    }

    private function navContentCentre(object $controller, string $name)
    {
        return '
        <div class="uk-search">
             <a href="" class="uk-search-icon-flip" uk-search-icon></a>
             <input type="search" class="uk-search-input uk-form-blank uk-border-bottom" onkeyup="tableFilter()" id="table_filter" placeholder="Filter ' . $name . '..." />
         </div>
        ';
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

    /**
     * Return an array of searchable column defined within the DataColumns class
     *
     * @param object $controller
     * @return void
     */
    private function getSearchableColumns(object $controller)
    {
        $searchables = $controller->getSearchableColumns($controller->column);
        if (is_array($searchables) && count($searchables) > 0) {
            $html = '<div uk-dropdown="mode: click">';
                $html .= '<ul class="uk-nav uk-nav-dropdown">';
                foreach ($searchables as $searchable) {
                    $html .= '<li>';
                    $html .= '<label for="filter-' . $searchable . '">';
                    $html .= '<input type="radio" name="filter" id="filter-' . $searchable . '" value="' . $searchable . '" class="uk-radio" />';
                    $html .= ' ' . str_replace('_', ' ', ucwords($searchable));
                    $html .= '</label>';
                    $html .= '</li>';
                }
                $html .= '</ul>';
            $html .= '</div>';
        }
        return $html;
    }
}
