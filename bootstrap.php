<?php

namespace NVAdvancedSearch;

new Plugin(  __DIR__ . '/advanced-search.php' );
new Deactivate(  __DIR__ . '/advanced-search.php' );
new Admin\Admin;
new Shortcode\Search;
