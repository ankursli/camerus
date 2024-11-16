<?php

use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;

Action::add('init', function () {
    add_rewrite_tag('%showroom-name%', '([^?]+)');
    add_rewrite_tag('%showroom-category%', '([^?]+)');
    add_rewrite_tag('%showroom-category-name%', '([^?]+)');
    add_rewrite_tag('%showroom-subcategory%', '([^?]+)');
    add_rewrite_tag('%showroom-page%', '([^?]+)');
    add_rewrite_tag('%showroom-page-number%', '([^?]+)');

    Filter::add('query_vars', function ($vars) {
        $vars[] = 'showroom-name';
        $vars[] = 'showroom-category';
        $vars[] = 'showroom-category-name';
        $vars[] = 'showroom-subcategory';
        $vars[] = 'showroom-page';
        $vars[] = 'showroom-page-number';

        return $vars;
    });

    add_rewrite_rule(
        '^showroom/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=showroom&showroom-name=$matches[1]&showroom-category=$matches[2]&showroom-category-name=$matches[3]&showroom-subcategory=$matches[4]&showroom-page=$matches[5]&showroom-page-number=$matches[6]',
        'top'
    );
    add_rewrite_rule(
        '^showroom/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=showroom&showroom-name=$matches[1]&showroom-category=$matches[2]&showroom-category-name=$matches[3]&showroom-subcategory=$matches[4]&showroom-page=$matches[5]',
        'top'
    );
    add_rewrite_rule(
        '^showroom/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=showroom&showroom-name=$matches[1]&showroom-category=$matches[2]&showroom-category-name=$matches[3]&showroom-subcategory=$matches[4]',
        'top'
    );
    add_rewrite_rule(
        '^showroom/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=showroom&showroom-name=$matches[1]&showroom-category=$matches[2]&showroom-category-name=$matches[3]',
        'top'
    );
    add_rewrite_rule(
        '^showroom/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=showroom&showroom-name=$matches[1]&showroom-category=$matches[2]',
        'top'
    );
    add_rewrite_rule(
        '^showroom/([-a-z0-9]+)/?$',
        'index.php?pagename=showroom&showroom-name=$matches[1]',
        'top'
    );
}, 10, 0);

Action::add('init', function () {
    add_rewrite_tag('%styleroom-name%', '([^?]+)');
    add_rewrite_tag('%styleroom-category%', '([^?]+)');
    add_rewrite_tag('%styleroom-category-name%', '([^?]+)');
    add_rewrite_tag('%styleroom-subcategory%', '([^?]+)');
    add_rewrite_tag('%styleroom-page%', '([^?]+)');
    add_rewrite_tag('%styleroom-page-number%', '([^?]+)');

    Filter::add('query_vars', function ($vars) {
        $vars[] = 'styleroom-name';
        $vars[] = 'styleroom-category';
        $vars[] = 'styleroom-category-name';
        $vars[] = 'styleroom-subcategory';
        $vars[] = 'styleroom-page';
        $vars[] = 'styleroom-page-number';

        return $vars;
    });

    add_rewrite_rule(
        '^styleroom/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=styleroom&styleroom-name=$matches[1]&styleroom-category=$matches[2]&styleroom-category-name=$matches[3]&styleroom-subcategory=$matches[4]&styleroom-page=$matches[5]&styleroom-page-number=$matches[6]',
        'top'
    );
    add_rewrite_rule(
        '^styleroom/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=styleroom&styleroom-name=$matches[1]&styleroom-category=$matches[2]&styleroom-category-name=$matches[3]&styleroom-subcategory=$matches[4]&styleroom-page=$matches[5]',
        'top'
    );
    add_rewrite_rule(
        '^styleroom/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=styleroom&styleroom-name=$matches[1]&styleroom-category=$matches[2]&styleroom-category-name=$matches[3]&styleroom-subcategory=$matches[4]',
        'top'
    );
    add_rewrite_rule(
        '^styleroom/([-a-z0-9]+)/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=styleroom&styleroom-name=$matches[1]&styleroom-category=$matches[2]&styleroom-category-name=$matches[3]',
        'top'
    );
    add_rewrite_rule(
        '^styleroom/([-a-z0-9]+)/([-a-z0-9]+)/?$',
        'index.php?pagename=styleroom&styleroom-name=$matches[1]&styleroom-category=$matches[2]',
        'top'
    );
    add_rewrite_rule(
        '^styleroom/([-a-z0-9]+)/?$',
        'index.php?pagename=styleroom&styleroom-name=$matches[1]',
        'top'
    );
}, 10, 0);