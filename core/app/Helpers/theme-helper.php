<?php

use App\Facades\ThemeDataFacade;


function getFooterWidgetArea()
{
    return ThemeDataFacade::getFooterWidgetArea();
}

function getHeaderNavbarArea()
{
    return ThemeDataFacade::getHeaderNavbarArea();
}

function getHeaderBreadcrumbArea()
{
    return ThemeDataFacade::getHeaderBreadcrumbArea();
}

function getAllThemeSlug()
{
    return ThemeDataFacade::getAllThemeSlug();
}

function loadCoreStyle()
{
    return ThemeDataFacade::loadCoreStyle();
}

function loadCoreScript()
{
    return ThemeDataFacade::loadCoreScript();
}
