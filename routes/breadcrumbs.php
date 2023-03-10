<?php

use App\Models\Category;
use App\Models\Feed;
use Diglactic\Breadcrumbs\Breadcrumbs;

use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Categories
Breadcrumbs::for('categories', function (BreadcrumbTrail $trail) {
    $trail->push(__('Categories'), route('categories.index'));
});

// Categories > [Create]
Breadcrumbs::for('categories.create', function (BreadcrumbTrail $trail) {
    $trail->parent('categories');
    $trail->push(__('Add category'));
});

// Categories > [Edit]
Breadcrumbs::for('categories.edit', function (BreadcrumbTrail $trail, Category $category) {
    $trail->parent('categories');
    $trail->push($category->name);
    $trail->push(__('Edit'));
});


// Feeds
Breadcrumbs::for('feeds', function (BreadcrumbTrail $trail) {
    $trail->push(__('Feeds'), route('feeds.index'));
});

// Feeds > [Create]
Breadcrumbs::for('feeds.create', function (BreadcrumbTrail $trail) {
    $trail->parent('feeds');
    $trail->push(__('Add feed'));
});

// Feeds > [Edit]
Breadcrumbs::for('feeds.edit', function (BreadcrumbTrail $trail, Feed $feed) {
    $trail->parent('feeds');
    $trail->push($feed->name);
    $trail->push(__('Edit'));
});
