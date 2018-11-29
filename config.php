<?php
// This file is part of The Bootstrap Moodle theme
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     theme_totara110base
 * @copyright   2014 Bas Brands, www.basbrands.nl
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author      Bas Brands
 * @author      David Scotson
 * @author      Joby Harding <joby.harding@totaralearning.com>
 */

defined('MOODLE_INTERNAL' || die());

$THEME->doctype = 'html5';
$THEME->name = 'totara110base';
$THEME->parents = array('roots', 'base');
$THEME->yuicssmodules = array();
$THEME->enable_dock = true;
$THEME->sheets = array('jquery-background-video', 'lean-slider', 'slidebars', 'animate', 'totara');
$THEME->supportscssoptimisation = false;
$THEME->enable_dock = true;

$THEME->rendererfactory = 'theme_overridden_renderer_factory';
$THEME->csspostprocess = 'theme_totara110base_process_css';

// Use CSS preprocessing to facilitate style inheritance.
$THEME->parents_exclude_sheets = array(
    'roots' => array('totara', 'totara-rtl'),
    'base' => array('flexible-icons'),
);

// We removed all references to side-post region in the array below to maintain ALtitude's 2-column layout

$THEME->layouts = array(
    // Most backwards compatible layout with blocks on the left - this is the layout used by default in Totara,
    // it is also the fallback when page layout is set too late when initialising page.
    // Standard Moodle themes have base layout without blocks.
    'base' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
    ),
    // Standard layout with blocks, this is recommended for most pages with general information.
    'standard' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
    ),
    // Main course page.
    'course' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
        'options' => array('langmenu' => true),
    ),
    'coursecategory' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
    ),
    // Part of course, typical for modules - default page layout if $cm specified in require_login().
    'incourse' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
    ),
    // The site home page.
    'frontpage' => array(
        'file' => 'frontpage.php',
        'regions' => array('side-pre', 'sidebar-block', 'welcome-block', 'action-full', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
        'options' => array('nonavbar' => false),
    ),
    // Server administration scripts.
    'admin' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
        'options' => array('fluid' => true),
    ),
    // This would be better described as "user profile" but we've left it as mydashboard
    // for backward compatibilty for existing themes. This layout is NOT used by Totara
    // dashboards but is used by user related pages such as the user profile, private files
    // and badges.
    'mydashboard' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'side-post'),
        'defaultregion' => 'side-pre',
    ),
    // The dashboard layout differs from the one above in that it includes a central block region.
    // It is used by Totara dashboards.
    'dashboard' => array(
        'file' => 'dashboard.php',
        'regions' => array('main', 'side-pre', 'side-post', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'main',
        'options' => array('langmenu' => true),
    ),
    // My public page.
    'mypublic' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
    ),
    'login' => array(
        'file' => 'login.php',
        'regions' => array(),
        'options' => array('langmenu' => true),
    ),

    // Pages that appear in pop-up windows - no navigation, no blocks, no header.
    'popup' => array(
        'file' => 'popup.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nonavbar' => true),
    ),
    // No blocks and minimal footer - used for legacy frame layouts only!
    'frametop' => array(
        'file' => 'default.php',
        'regions' => array(),
        'options' => array('nofooter' => true, 'nocoursefooter' => true),
    ),
    // Embeded pages, like iframe/object embeded in moodleform - it needs as much space as possible.
    'embedded' => array(
        'file' => 'embedded.php',
        'regions' => array()
    ),
    // Used during upgrade and install, and for the 'This site is undergoing maintenance' message.
    // This must not have any blocks, links, or API calls that would lead to database or cache interaction.
    // Please be extremely careful if you are modifying this layout.
    'maintenance' => array(
        'file' => 'maintenance.php',
        'regions' => array(),
    ),
    // Should display the content and basic headers only.
    'print' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'options' => array('nofooter' => true, 'nonavbar' => false),
    ),
    // The pagelayout used when a redirection is occuring.
    'redirect' => array(
        'file' => 'embedded.php',
        'regions' => array(),
    ),
    // The pagelayout used for reports.
    'report' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
    ),
    // The pagelayout used for safebrowser and securewindow.
    'secure' => array(
        'file' => 'default.php',
        'regions' => array('side-pre', 'sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'side-pre',
        'options' => array('nofooter'=>true, 'nonavbar'=>true, 'nocustommenu'=>true, 'nologinlinks'=>true, 'nocourseheaderfooter'=>true),
    ),
    // Totara noblocks layout - but with necessary blocks.
    'noblocks' => array(
        'file' => 'default.php',
        'regions' => array('sidebar-block', 'footer-one', 'footer-two', 'footer-three', 'footer-four'),
        'defaultregion' => 'sidebar-block',
        'options' => array('noblocks' => false, 'langmenu' => true),
    ),
);

$THEME->javascripts_footer = array(
    'jquery-background-video',
    'lean-slider',
    'slidebars',
    'activate'
);
